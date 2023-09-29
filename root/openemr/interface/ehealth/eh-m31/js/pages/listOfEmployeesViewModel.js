/**********************************************************
 *  listOfEmployeesViewModel.js
 *  List of Employees
 *
 *  E-health sub-module 3.1.3   
 *
 *  OpenEMR     
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function ListOfEmployeesViewModel() {
	var server = "/openemr/interface/ehealth";
	var self = this;
	var spinner = $('#loading');

    self.filter_division_id = ko.observable();
    self.filter_position_id = ko.observable();
    self.filter_start_date = ko.observable();
    self.filter_name = ko.observable();
    self.filter_status = ko.observable();

    //---------------------------------
    //                 Get Division List
    //---------------------------------
	self.divisions = ko.observableArray([]);
    self.isFilterDismissed = ko.observable(false);

    self.getDivisions = function () {
        var org_id = document.getElementById("org_id").value;
		if (org_id) {
			spinner.show();
            $.ajax({
                url: server + "/api/LegalEntityData/GetDivisionList.php",
				type: "POST",
				data: { org_id: org_id },
				success: function (response) {
					var result = CheckErrorsInResponseAndGetData(response);
					if (!result)
						return;
					var data = result.data;
                    if (data) {
                        self.divisions.removeAll();
                        var options = [];
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                options.push({ value: data[key].id, text: data[key].name });
                            }
                        }
                        sortArrayOfPairs(options);
                        options.unshift(
                            {value: '-1', text: 'Всі підрозділи'},
                            {value: '', text: 'Без підрозділу'});
                        self.divisions.push.apply(self.divisions, options);
                    }
                },
				error: function (xhr, ajaxOptions, thrownError) {
					NotificationAboutErrorDuringProcess(LoadingProcess_text, "списку підрозділів", xhr, thrownError);
				},
				complete: function () {
					spinner.hide();
				}
            });
        }
    };
    self.getDivisions();

    //-------------------------------
    //                      Positions
    //-------------------------------
    self.positions = ko.observableArray([]);
    self.getPositions = function() {
        var request = $.ajax({
            url: server + "/api/Dictionaries/Positions.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function(data) {
            self.positions.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.positions.push.apply(self.positions, options);
        });
    };
    self.getPositions();

    //-------------------------------
    //               Get Employee List
    //-------------------------------
    self.employees = ko.observableArray([]);
    self.getEmployees = function (onSuccess) {
        var org_id = document.getElementById("org_id").value;
        if (org_id) {
            var division_id = self.filter_division_id();
            var fetch_all = '0';
            if ('-1' === division_id) {
                fetch_all = '1';
                division_id = '';
			}
			spinner.show();
            $.ajax({
                url: server + "/api/LegalEntityData/GetEmployeeList.php",
                type: "POST",
				data: {
					org_id: org_id.toLocaleLowerCase(),
					division_id: division_id ? division_id.toLocaleLowerCase() : "",
					status: self.filter_status()
				},
                success: function (response) {
					var result = CheckErrorsInResponseAndGetData(response);
					if (!result)
						return;
					if (result && result.data) {
						var data = result.data;
                        self.employees.removeAll();
                        var filtered = true;
                        var options = [];
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                var obj = data[key];
                                var fullName = obj.party.last_name + " " + obj.party.first_name + " " + obj.party.second_name;
                                if (obj.division) {
                                    var division_name = obj.division.name;
                                    var division_href = server + "/eh-m31/Division.php?org_id="  +  org_id  +  "&id=" + obj.division.id; // !!!DIVISION + legal_entity_id + division.id !!!
                                }
                                if (self.filter_start_date() && obj.start_date < self.filter_start_date())
                                    continue;
                                if (self.filter_position_id() && obj.position !== self.filter_position_id())
                                    continue;
                                if (self.filter_status() && obj.status !== self.filter_status())
                                    continue;
                                if (self.filter_name() && -1 === fullName.toLocaleUpperCase().indexOf(
                                    self.filter_name().toLocaleUpperCase()))
                                    continue;
                                options.push({
                                    start_date: obj.start_date,
                                    end_date: obj.end_date,
                                    fullName: fullName,
                                    position: self.convertEmplPosition(obj.position),
                                    status: self.convertEmplStatus(obj.status),
                                    division_name: division_name,
                                    division_href: division_href,
                                    href:  "/openemr/interface/ehealth/eh-m31/Employee.php?id=" + obj.id 	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! EMPLOYEE + obj.id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                                });
                            }
                        }
                        self.employees.push.apply(self.employees, options);
                        // console.log(self.employees());
                        if (onSuccess) onSuccess(self.employees());
                    }
                },
				error: function (xhr, ajaxOptions, thrownError) {
					NotificationAboutErrorDuringProcess(LoadingProcess_text, "списку правцівників", xhr, thrownError);
				},
				complete: function () {
					spinner.hide();
				}
            });
        }
    };

    self.convertEmplPosition = function(status) {
        for (var key in self.positions()) {
            if (self.positions()[key].value === status)
                return self.positions()[key].text;
            //if (data.hasOwnProperty(key)) {
                /*if (data[key].id.toUpperCase() == division_id) {
                    options = [{ value: data[key].id, text: data[key].name }];
                    //self.divisions.push.apply(self.divisions, options);
                    //document.getElementById("divisionList").disabled = true;
                    break;
                }*/
            //}
        }
    };
	self.convertEmplStatus = function (status) {
		if (status === "APPROVED")
			return "Активний";
		else if (status === "NEW")
			return "Новий";
        else
            return "Звільнений";
    };

    self.openResults = function () {
        if (self.filter_status() === 'DISMISSED') {
            self.isFilterDismissed(true);
        } else {
            self.isFilterDismissed(false);
        }

        self.employees.removeAll();
		var onSuccess = function (data) {
			if (document.getElementById("datatable-hide-columns").classList.contains("hide")) {
				var active = document.querySelector("#datatable-hide-columns");
				active.classList.remove("hide");
			}
            if (data.length === 0) {
                console.log("Працівники відсутні");     // instead of asked variants will be text 'no result'
            }
        };
        self.getEmployees(onSuccess);
    };
}