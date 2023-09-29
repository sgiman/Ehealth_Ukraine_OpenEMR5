/****************************************
 *  createDivision.js
 *  Create Division
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
function CreateDivisionViewModel() {
	var self = this;
	var server = "/openemr/interface/ehealth";
	var spinner = $('#loading');

	self.division = ko.observable(new DivisionViewModel());
	self.editable = ko.observable(false);

	self.FormatDataForSending = function () {
		var temp = JSON.parse(ko.toJSON(self.division().getNeededValues()));
		return temp;
	};
	self.showDivision = function () {
		// console.log(ko.toJSON(self.FormatDataForSending()));
	};

	//-----------------------------
	//				Register Division
	//-----------------------------
	self.createDivision = function () {
		if (self.errors().length !== 0) {
			$('div.alert-danger').show();
			self.errors.showAllMessages();
			return;
		}
		
		$('div.alert-danger').hide();
		spinner.show();
		
		//var reqBody = Base64.encode(ko.toJSON(self.FormatDataForSending()));			
		var reqBody = ko.toJSON(self.FormatDataForSending());
		
		if (reqBody) {
			
			console.log('********* DivisionCreate/reqBody', reqBody);	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			
			$.ajax({
				url: server + "/api/LegalEntityData/CreateDivision.php",
				type: "POST",
				data: {
					data: reqBody
				},
				
				success: function (response) {
					var result = CheckErrorsInResponseAndGetData(response);

					if (!result)
						return;

					// console.log(result);
				
					if (result.data) {
						var onClosed = function () {
							window.location.href = "/openemr/interface/ehealth/eh-m31/ListOfDivisions.php";
						};
						NotificationInsteadOfAlert("Успішне виконання", ["Підрозділ було " + (self.division().id() ? "редаговано." : "створено.")], onClosed);
					}
					else if (result.meta.code == 500) {
						NotificationInsteadOfAlert("Помилка", ["На стороні серверу трапилась якась помилка."]);
					}
					else if (result.error) {
						NotificationInsteadOfAlert("У заповнених Вами данних були виявлені наступні помилки:", GetNeededTextListWithErrors(result));
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					NotificationAboutErrorDuringProcess(CreatingOrUpdatingProcess_text, "підрозділу", xhr, thrownError);
				},
				complete: function () {
					spinner.hide();
				}
			});
		}
	};

/*
	//-----------------------------
	//				Deactive Division
	//-----------------------------
	self.deactiveDivision = function () {
		spinner.show();
		$.ajax({
			url: server + "/api/LegalEntityData/DeactiveDivision.php",
			type: "POST",
			data: { id: self.division().id() ? self.division().id() : "" },
			success: function (response) {
				var result = CheckErrorsInResponseAndGetData(response);
				if (!result)
					return;
				// console.log(result);
				if (result.data) {
					var onClosed = function () {
						window.location.href = "/ListOfDivisions.php";
					};
					NotificationInsteadOfAlert("Успішне виконання", ["Підрозділ було деактивовано."], onClosed);
				}
				else if (result.meta.code == 500) {
					NotificationInsteadOfAlert("Помилка", ["На стороні серверу трапилась якась помилка."]);
				}
				else if (result.error) {
					NotificationInsteadOfAlert("Були виявлені наступні помилки:", GetNeededTextListWithErrors(result));
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				NotificationAboutErrorDuringProcess(DeletingProcess_text, "підрозділу", xhr, thrownError);
			},
			complete: function () {
				spinner.hide();
			}
		});
	};
*/

	self.allow_edit = function () {
		self.editable(true);
	};

	self.show_edit_button = function () {
		return self.division().id() && !self.editable();
	};

	self.disable_input = function () {
		return self.division().id() && !self.editable();
	};

	self.show_button = function () {
		return self.editable() || !self.division().id();
	};

	//-----------------------------
	//				Get Division List
	//-----------------------------
	self.getDivisionById = function (org_id, id) {
		spinner.show();
		$.ajax({
			url: server + "/api/LegalEntityData/GetDivisionList.php",
			type: "POST",
			data: { org_id: org_id },
			success: function (response) {
				var result = CheckErrorsInResponseAndGetData(response);
				if (!result)
					return;
				if (result && result.data) {
					var data = result.data;
					for (var d = 0; d < data.length; d++) {
						var item = data[d];
						if (id === item.id) {
							self.division().id(item.id);
							self.division().name(item.name);
							self.division().status(item.status);
							
							setTimeout(function () { self.division().type(item.type); }, 2000);
							//self.division().type(item.type);
							
							self.division().email(item.email);

							if (item.location && item.location['latitude'] && item.location['longitude']) {
								var location = new LocationViewModel();
								location.latitude(item.location['latitude']);
								location.longitude(item.location['longitude']);
								self.division().location(location);
							}
							self.division().addresses.removeAll();
							ko.utils.arrayForEach(item.addresses,
								function (item, index) {
									var address = new AddressViewModel(index);
									address.initValues(item);
									self.division().addresses.push(address);
								});

							self.division().phones.removeAll();
							for (var f = 0; f < item.phones.length; f++) {
								var phone = new PhoneViewModel();
								setTimeout(function () { phone.type(item.phones[f].type); }, 2000);								
								//phone.type(item.phones[f].type);
								phone.number(item.phones[f].number);
								self.division().phones.push(phone);
							}
							break;
						}
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				NotificationAboutErrorDuringProcess(LoadingProcess_text, "даних про підрозділ", xhr, thrownError);
			},
			complete: function () {
				spinner.hide();
			}
		});
	};

	self.errors = ko.validation.group(this);
}
