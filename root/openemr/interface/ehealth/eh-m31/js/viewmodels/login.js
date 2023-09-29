/**********************************************************
 *  login.js
 *  Логін  
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
'use strict';
function LoginViewModel() {
	var self = this;
	var server = "/openemr/interface/ehealth";
	
    var spinner = $('#loading');

	self.edrpou = ko.observable();
	self.orgNames = ko.observableArray([]);
	self.orgId = ko.observable();

	self.emplEmail = ko.observable();
	self.emplData = ko.observableArray([]);
	self.emplId = ko.observable();

	self.orgId.subscribe(function () {
		self.emplData([]);
	});

	//--------------------------------- 
	//      Legal Entity Types (Dictionaries)
	//--------------------------------- 
	self.legalEntityTypes = ko.observableArray([]);
	self.getLegalEntityTypes = function () {
		var request = $.ajax({
			url: server + "/api/Dictionaries/LegalEntityTypes.php",
			type: "GET",
			datatype: "json"
		});
		request.done(function (data) {
			self.legalEntityTypes.removeAll();
			var options = [];
			for (var key in data.values) {
				if (data.values.hasOwnProperty(key)) {
					if (data.values[key].code.toUpperCase() !== "MIS")
						options.push({ value: data.values[key].code, text: data.values[key].name });
				}
			}
			sortArrayOfPairs(options);
			self.legalEntityTypes.push.apply(self.legalEntityTypes, options);
		});
	};
	self.getLegalEntityTypes();

    self.getOrgNames = function () {
		var edrpou = self.edrpou();
        var re = new RegExp("^[0-9]([0-9]{7}|[0-9]{9})$");

		if (!re.test(edrpou))
		{
			self.orgId("");
			self.orgNames([]);
			NotificationInsteadOfAlert("Помилка", ["Некорректно введений номер ЄДРПОУ або РКНОПП (ІПН) медичної установи."]);
			return;
		}
		spinner.show();
		
	//--------------------------------- 
	//              Get Legal Entities V2 
	//                    Login & Edit
	//--------------------------------- 
		$.ajax({
			url: server + "/api/LegalEntityData/GetLegalEntitiesV2.php",     //GetLENameByEDRPOU",   //GetLegalEntitiesV2
			type: "POST",
			data: { edrpou: edrpou },
			success: function (response) {
				var result = CheckErrorsInResponseAndGetData(response);

				self.orgNames.removeAll();
				self.emplData([]);
				self.emplId("");

				if (!result)
					return;
				var data = result.data;
				
				if (data) {
					if (data.length && data.length > 0) {
						data.forEach(function (LE) {
							var name = LE.edr ? LE.edr.short_name || LE.edr.name : LE.name || " ";
                            if (LE.id && LE.type) {
								var extraTextWithType = getTextFromDictWithValue(LE.type, self.legalEntityTypes());
								if (extraTextWithType)
                                    extraTextWithType = ' (' + extraTextWithType + ')';
                                self.orgNames.push({ text: name + extraTextWithType, value: LE.id, type: LE.type });
							} else {
								NotificationInsteadOfAlert("Помилка", ["Помилка при завантаженні данних."]);
							}
							if (!LE.edr) {
								NotificationInsteadOfAlert("Помилка", ["У базі відсутні деякі з данних (з ЄДР). Можливо Вам потрібно переєструвати медичний заклад або це сталося в результаті помилки на стороні серверу."]);
							}
						});
					}
				
					if (data.length === 0)
						NotificationInsteadOfAlert("Помилка", ["Помилка при завантаженні данних. Немає медичних установ із таким ЄДРПОУ."]);
					
				} else if (result.error && result.error.message) {
					NotificationInsteadOfAlert("Помилка", GetNeededTextListWithErrors(result));
				} //else
					//console.log('******** LOGIN: RESULT: ', result); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! RESULT !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!					
			},
			error: function (xhr, ajaxOptions, thrownError) {
				NotificationInsteadOfAlert("Помилка", ["Статус - " + xhr.status, xhr.responseText, "Помилка - " + thrownError]);
			},
			complete: function () {
				spinner.hide();
			}
		});
	};
	
	self.getEmplNames = function () {
		var emplEmail = self.emplEmail();
		var re = new RegExp("^[a-zA-Z0-9.!#$%&�*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$");

		if (!re.test(emplEmail))
		{
			self.emplId("");
			NotificationInsteadOfAlert("Помилка", ["Некорректно введений email працівника."]);
			return;
		}
        spinner.show();
        var currentLegalEntityType = self.orgNames().find(org => org.value == self.orgId()).type;
		
	//--------------------------------- 
	//          Get Employee Names List
	//--------------------------------- 
		$.ajax({
			url: server + "/api/LegalEntityData/GetEmployeeNamesList.php",
			type: "POST",
			data: {
                orgId: self.orgId(),
                legalEntityType: currentLegalEntityType,
				email: emplEmail
			},
			success: function (response) {
				var result = CheckErrorsInResponseAndGetData(response);
				if (!result)
					return;
				var users = result.Users;
				if (users) {
					self.emplData.removeAll();
					if (users.length === 0) {
						NotificationInsteadOfAlert("Помилка", ["Жоден працівник не був знайдений по заданому email."]);
						self.emplData([]);
					}
					else {
						var optionsData = [];
						for (var key in users) {
							if (users.hasOwnProperty(key)) {
								var FIOwithPos = users[key].FullName;
								if (users[key].PositionName)
									FIOwithPos += " (" + users[key].PositionName + ")";  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! NAME-POSITION !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
									optionsData.push({ name: FIOwithPos, id: users[key].ID });
									
									//set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
									set_cookie ( 'user', FIOwithPos, '', '', '', '/', '');
									console.log('*****COOKIE/HEADER: ', get_cookie('user'));
									
							}
						}
						self.emplData.push.apply(self.emplData, optionsData);
					}
				} else
					NotificationInsteadOfAlert("Помилка", ["Помилка при завантаженні данних."]);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				NotificationInsteadOfAlert("Помилка", ["Статус - " + xhr.status, "Помилка - " + thrownError]);
			},
			complete: function () {
				spinner.hide();
			}
		});
	};

	self.logIntoCabinet = function () {
		var employeeName = self.emplData().find(function (obj) { return obj.id === self.emplId(); })['name'];
		if (self.emplId() === "")
		{
			if (employeeName.indexOf('@') === -1) {
				NotificationInsteadOfAlert("Помилка", ["Працівник не був вибраний зі списку або не знайдений."]);
			}
		}
		spinner.show();
		
		// ????????????????????????????????--- Login ---???????????????????????????????
		//------------------------
		//              User Login
		//------------------------
		$.ajax({
			url: "/openemr/interface/ehealth/eh-m31/home/user_login.php", 
			type: "POST",
			data: {
				emplID_input: self.emplId()
				//pw_input: "",
				//name_position: employeeName
			},
			success: function (result) {
				if (result && result.appData === "") {
					NotificationInsteadOfAlert("Помилка", ["Помилка на стороні серверу. Спробуйте перезавантажити сторінку."]);
				} else {
					location.href = "home/ehealth_login.php?url=" + result.appData;
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				// console.log(xhr.responseText);
				NotificationInsteadOfAlert("Помилка", ["Статус - " + xhr.status, xhr.responseText, "Помилка - " + thrownError]);
			},
			complete: function () {
				spinner.hide();
			}
		});
	};
}

function getTextFromDictWithValue(value, dict) {
	if (!dict || !dict.length)
		return value;
	for (var i = 0; i < dict.length; i++) {
		if (dict[i].value === value)
			return dict[i].text;
	}
    return value;
}
