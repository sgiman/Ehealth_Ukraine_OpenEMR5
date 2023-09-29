/****************************************
 *  employee.js
 *  Employee
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
function EmployeeViewModel() {
    var self = this;
	var server = "/openemr/interface/ehealth";

    self.position = ko.observable().extend({ required: true });
    self.start_date = ko.observable().extend({ required: true });
    self.end_date = ko.observable();
    self.status = ko.observable();       //.extend({ required: true });    value will be set during data formating for sending
    self.employee_type = ko.observable().extend({ required: true });
    
    self.division_id = ko.observable();
    self.legal_entity_id = ko.observable();
    self.employee_id = ko.observable();
    self.division = ko.observable();
    self.legal_entity = ko.observable();
    self.party = ko.observable(new PartyViewModel()).extend({ required: true });
    self.specialist = ko.observable(new  SpecialistViewModel());

    //------------------------------
	//    EmployeeStatuses (Dictionaries)
    //------------------------------
	self.employeeStatuses = ko.observableArray([]);         //  maybe no need in it
    self.getEmployeeStatuses = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/EmployeeStatuses.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.employeeStatuses.removeAll();
            
			//console.log('****EmployeeStatuses = ', data.values); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            
			var options = [];
			
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
			
            //console.log(options);          
			self.employeeStatuses.push.apply(self.employeeStatuses, options);  
			
		});
    };
	self.getEmployeeStatuses();

    //-----------------------------
	//         Positions (Dictionaries)
    //-----------------------------
    self.positionTypes = ko.observableArray([]);
    self.getPositionTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/Positions.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.positionTypes.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.positionTypes.push.apply(self.positionTypes, options);
        });
    };
    self.getPositionTypes();

    //-----------------------------
	//    EmployeeTypes (Dictionaries)
    //-----------------------------
    self.employeeTypes = ko.observableArray([]);
    self.getEmployeeTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/EmployeeTypes.php",
            type: "GET",
            datatype: "json"
        });
        
		request.done(function (data) {
            self.employeeTypes.removeAll();
            //console.log('********employeeTypes / data.values = ', data.values);		// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            var options = [];
            for (var key in data.values) {
                //if (data.values.hasOwnProperty(key) && availableEmployeeTypes.includes(data.values[key].code)) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.employeeTypes.push.apply(self.employeeTypes, options);
        });
    };
    self.getEmployeeTypes();

    //-----------------------------
	//         Get Employee Details
    //-----------------------------
    self.initEmployee = function (emp_id) {
		console.log("****** emp_id =  ", emp_id);
		if (emp_id) {
			spinner.show();
			$.ajax({
				url: server + "/api/LegalEntityData/GetEmployeeDetails.php",
				type: "POST",
				data: { emp_id: emp_id },
				
				success: function (response) {				
					var result = CheckErrorsInResponseAndGetData(response);					
					
					if (!result)
						return;
					
					if (result) {
						self.employee_id(result.id);
						
						setTimeout(function () { self.position(result.position); }, 4000);
						//self.position(result.position);						
						
						setTimeout(function () { self.employee_type(result.employee_type); }, 4000);
						//self.employee_type(result.employee_type);
						
						self.start_date(result.start_date);												
						if (result.end_date) self.end_date(result.end_date);						
						self.status(result.status);
						self.party().email(result.email);
						self.party().id(result.party.id);
						self.party().first_name(result.party.first_name);
						self.party().last_name(result.party.last_name);
						self.party().second_name(result.party.second_name);
						self.party().birth_date(result.party.birth_date);
						
						setTimeout(function () { self.party().gender(result.party.gender); }, 4000);
						//self.party().gender(result.party.gender);
						
						self.party().no_tax_id(result.party.no_tax_id);
						self.party().tax_id(result.party.tax_id);
						self.party().documents.removeAll();
						
						ko.utils.arrayForEach(result.party.documents,
							function (item) {
								var document = new DocumentViewModel();
								document.initValues(item);
								self.party().documents.push(document);
							});

						self.party().phones.removeAll();
						
						ko.utils.arrayForEach(result.party.phones,						
							function (item) {
								var phone = new PhoneViewModel();								
								
								setTimeout(function () { phone.type(item['type']); }, 2000);
								//phone.type(item['type']);								
								
								phone.number(item['number']);
								self.party().phones.push(phone);
							});

						self.specialist().educations.removeAll();
						self.specialist().qualifications.removeAll();
						self.specialist().specialities.removeAll();
						self.specialist().science_degree.removeAll();
						
						// --- SPECIALIST (DOCTOR) ---
					
						if (result.specialist) {
							console.log('=*****= result.specialist = ', result.specialist);	// !!!!!!!!!!!!!!!!!!!!!!!!
							ko.utils.arrayForEach(result.specialist.educations,
								function (item) {
									var education = new SpecialistEducationViewModel();
									education.country(item['country']);
									education.city(item['city']);
									education.institution_name(item['institution_name']);
									if (item['issued_date']) education.issued_date(item['issued_date']);
									education.diploma_number(item['diploma_number']);	
									
									setTimeout(function () { education.degree(item['degree']); }, 2000);
									//education.degree(item['degree']);									

									setTimeout(function () { education.speciality(item['speciality']); }, 2000);
									//education.speciality(item['speciality']);

									self.specialist().educations.push(education);
								});

                            ko.utils.arrayForEach((result.specialist.qualifications ? result.specialist.qualifications : []),
								function (item) {
									var qualification = new  SpecialistQualificationViewModel();
									
									setTimeout(function () { qualification.type(item['type']); }, 2000);
									//qualification.type(item['type']);
									
									qualification.institution_name(item['institution_name']);							

									setTimeout(function () { qualification.speciality(item['speciality']); }, 2000);
									//qualification.speciality(item['speciality']);									

									if (item['issued_date']) qualification.issued_date(item['issued_date']);
									if (item['certificate_number']) qualification.certificate_number(item['certificate_number']);
									if (item['valid_to']) qualification.valid_to(item['valid_to']);
									if (item['additional_info']) qualification.additional_info(item['additional_info']);
									self.specialist().qualifications.push(qualification);
								});

							ko.utils.arrayForEach(result.specialist.specialities,
								function (item) {
									var speciality = new SpecialistSpecialityViewModel();
									
									setTimeout(function () { speciality.speciality(item['speciality']); }, 2000);
									//speciality.speciality(item['speciality']);
									
									speciality.speciality_officio(item['speciality_officio']);
									
									setTimeout(function () { speciality.level(item['level']); }, 2000);
									//speciality.level(item['level']);									
									
									setTimeout(function () { speciality.qualification_type(item['qualification_type']); }, 2000);
									//speciality.qualification_type(item['qualification_type']);									
									
									speciality.attestation_name(item['attestation_name']);
									speciality.attestation_date(item['attestation_date']);
									if (item['valid_to_date']) speciality.valid_to_date(item['valid_to_date']);
									speciality.certificate_number(item['certificate_number']);
									self.specialist().specialities.push(speciality);
								});
							var resultScienceDegree = result.specialist.science_degree;
							
							if (resultScienceDegree && resultScienceDegree.degree && resultScienceDegree.city && resultScienceDegree.diploma_number && resultScienceDegree.speciality) {
								var science_degree = new SpecialistScienceDegreeViewModel();
								science_degree.country(resultScienceDegree['country']);
								science_degree.city(resultScienceDegree['city']);
								
								setTimeout(function () { science_degree.degree(resultScienceDegree['degree']); }, 2000);
								//science_degree.degree(resultScienceDegree['degree']);								
								
								science_degree.institution_name(resultScienceDegree['institution_name']);
								science_degree.diploma_number(resultScienceDegree['diploma_number']);
								
								setTimeout(function () { science_degree.speciality(resultScienceDegree['speciality']); }, 2000);
								//science_degree.speciality(resultScienceDegree['speciality']);
								
								science_degree.issued_date(resultScienceDegree['issued_date']);
								self.specialist().science_degree.push(science_degree);
							}
						}

						if (result.division) {
							self.division(result.division);
							setTimeout(function () { self.division_id(result.division.id); }, 4000);
							//self.division_id(result.division.id);
						}

						self.legal_entity(result.legal_entity);
						self.legal_entity_id(result.legal_entity.id);
						
					} // --- if (result) ---
				}, // --- success: ---
				
				error: function (xhr, ajaxOptions, thrownError) {
					NotificationAboutErrorDuringProcess(LoadingProcess_text, "даних про співробітника", xhr, thrownError);
				},
				
				complete: function () {
					spinner.hide();
				}
				
			}); // --- $.ajax ---
			
		} else {
			console.log("Відсутній emp_id");
		}
		
    };

    //-----------------------------
	//                   ko.computed
    //-----------------------------
    self.type_name = ko.computed(function () {
        for (var i = 0; i < self.employeeTypes().length; i++) {
            var t = self.employeeTypes()[i];
            if (self.employee_type() === t.value) {
                return t.text;
            }
        }
    }, this);

    //-----------------------------
	//               getNeededValues
    //-----------------------------
	self.getNeededValues = function () {
		var temp = {};
		temp.division_id = returnIfDataExist(self.division_id());
		temp.position = returnIfDataExist(self.position());
		temp.start_date = returnIfDataExist(self.start_date());
		temp.end_date = returnIfDataExist(self.end_date());
		temp.employee_type = returnIfDataExist(self.employee_type());
		temp.party = self.party().getNeededValues();

		// SPECIALIST (DOCTOR)
		if (self.employee_type() === "SPECIALIST" ) {
			temp.specialist = self.specialist().getNeededValues();
		} else temp.specialist = (function () { return; })();
	
		return temp;
	};
}
