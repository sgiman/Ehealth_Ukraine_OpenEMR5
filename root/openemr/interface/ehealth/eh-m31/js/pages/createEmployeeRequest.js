/****************************************
 *  createEmployeeRequest.js
 *  Create Employee Request
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
'use strict';
//*************************************************
//      Create Employee Request View Model
//*************************************************
function CreateEmployeeRequestViewModel(org_id, division_id, employee_id) {
	var self = this;
	var server = "/openemr/interface/ehealth";
	var spinner = $('#loading');

    self.employee_request = ko.observable(new EmployeeViewModel()); 
    self.org_id = ko.observable(org_id);
    self.division_id = ko.observable(division_id);
    self.employee_id = ko.observable(employee_id);
    self.editable = ko.observable(false);
    self.signedData = ko.observableArray([]);           // [0]  -  data, [1]  -  signed data.

    //------ allow_edit ------
	self.allow_edit = function () {
        self.editable(true);
    };

    //------ show_edit_button ------
    self.show_edit_button = function () {
		return self.employee_id() && !self.editable();
    };

    //------ disable_input ------
    self.disable_input = function () {
        return self.employee_id() && !self.editable();
    };

    //------ show_button ------
    self.show_button = function () {
        return self.editable() || !self.employee_id();
    };

//----------------------------------
//                   show_full_ProfInfo
//----------------------------------
    self.show_full_ProfInfo = function () {
        if (['DOCTOR', 'PHARMACIST', 'SPECIALIST'].indexOf(self.employee_request().employee_type()) >= 0)
            return true;
        else
            return false;
    };

//----------------------------------
//       !!!!  Format Data for Sending !!!!
//----------------------------------
    self.FormatDataForSending = function () {
        var temp = { employee_request: self.employee_request().getNeededValues() };

        temp.employee_request.status = "NEW";                            ///  during creating new employee or updating
        temp.employee_request.legal_entity_id = returnIfDataExist(self.org_id());
        //temp.employee_request.employee_id = returnIfDataExist(self.employee_id());
        console.log('******FormatDataForSending / temp = ', temp);
        return temp;
    };
    self.showLegal = function () {
        console.log('******FormatDataForSending / toJSON = ', ko.toJSON(self.FormatDataForSending()));
    };


//----------------------------------
//           !!! Open Signing Modal !!!
//----------------------------------
//???????????????????????????????????????????????????
    self.openSigningModal = function () {
		
        if (legalEntityType == "PHARMACY") {
            self.employee_request().division_id.extend({ required: true });
        }
        var errors = self.errors().length;

		if (self.employee_request().employee_type() !== "DOCTOR" && self.employee_request().employee_type() !== "PHARMACIST" && self.employee_request().employee_type() !== "SPECIALIST") {
            errors = errors - self.employee_request().specialist().specialistErrors().length;
        }

        if (errors !== 0) {
            $('div.alert-danger').show();
            self.errors.showAllMessages();
            return;
        }
        $('div.alert-danger').hide();

        SettingsBeforeOpeningModalForSigning();  						 //  Opening Modal for Signing !!!
		
    };
    
    self.onclickSignFile = function () {										// Sign File !!!
        var declData = ko.toJSON(self.FormatDataForSending());	// JSON (FormatDataForSending) !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        onSignFile(declData, self.createEmployeeRequest);
    };


	//--------------------------------------
	// !!! Create or Update Employee Request !!!
	//--------------------------------------
    self.createEmployeeRequest = function (data) {
        //console.log('****** signedData-Decode / createEmployeeRequest = ', Base64.decode(self.signedData()[0]));
        //console.log('****** signedData [0] / createEmployeeRequest = ', self.signedData()[0]);

        //var reqBody = self.signedData()[0];
        var reqBody = ko.toJSON(self.FormatDataForSending());	// reqBody (FormatDataForSending) !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        
		console.log('********* reqBody = ', reqBody); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		
		if (reqBody) {
			spinner.show();
            $.ajax({
                url: server + "/api/LegalEntityData/CreateOrUpdateEmployeeRequest.php",
                type: "POST",
                data: { emplData: reqBody },
				success: function (response) {
					var result = CheckErrorsInResponseAndGetData(response);
					if (!result)
						return;
                    if (result.data) {
						var onClosed = function () {
							window.location.href = "/openemr/interface/ehealth/eh-m31/ListOfEmployees.php";
						};
						NotificationInsteadOfAlert("Успішне виконання", ["Працівника було " + (self.employee_id() ? "редаговано." : "створено.")], onClosed);
                    } else if (result.meta.code == 500) {
                        NotificationInsteadOfAlert("Помилка", ["На стороні серверу трапилась якась помилка."]);
                    } else if (result.error) {
                        NotificationInsteadOfAlert("У заповнених Вами данних були виявлені наступні помилки:", GetNeededTextListWithErrors(result));
                    }
                },
				error: function (xhr, ajaxOptions, thrownError) {
					NotificationAboutErrorDuringProcess(CreatingOrUpdatingProcess_text, "співробітника", xhr, thrownError);
				},
				complete: function () {
					spinner.hide();
				}
            });
        }
    };

	//------------------------------
	//               Get Division List 
	//------------------------------
    self.divisions = ko.observableArray([]);
    self.getDivisions = function () {
        var org_id = self.org_id();
        var division_id = self.division_id();
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
                    self.divisions.removeAll();
                    var options = [{ value: null, text: "Не обраний" }];    // able to create employee out of division
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            options.push({ value: data[key].id, text: data[key].name });
                        }
                    }
                    self.divisions.push.apply(self.divisions, options);
                    if (division_id) {
                        for (var key in options) {
                            if (options.hasOwnProperty(key)) {
                                if (options[key].value && options[key].value.toUpperCase() === division_id.toUpperCase()) {
                                    $("#divisionList").val(options[key].value);
                                    break;
                                }
                            }
                        }
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

	//------------------------------
	//   Dismissed (Deactivate) Employee
	//------------------------------
    self.deactivateEmployee = function () {
        spinner.show();
        $.ajax({
            url: server + "/api/LegalEntityData/DismissedEmployee.php",
            type: "POST",
            data: { emp_id: self.employee_id() ? self.employee_id() : "" },
            success: function (response) {
                var result = CheckErrorsInResponseAndGetData(response);
                if (!result)
                    return;
                console.log('****** RESULT = ', result);	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                if (result.data) {
                    var onClosed = function () {
                        window.location.href = "/openemr/interface/ehealth/eh-m31/ListOfEmployees.php";
                    };
                    NotificationInsteadOfAlert("Успішне виконання", ["Працівник був звільнений."], onClosed);
                }
                else if (result.meta.code == 500) {
                    NotificationInsteadOfAlert("Помилка", ["На стороні серверу трапилась якась помилка."]);
                }
                else if (result.error) {
                    NotificationInsteadOfAlert("Були виявлені наступні помилки:", GetNeededTextListWithErrors(result));
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                NotificationAboutErrorDuringProcess(DeletingProcess_text, "працівника", xhr, thrownError);
            },
            complete: function () {
                spinner.hide();
            }
        });
    };
    self.errors = ko.validation.group(this);

}
