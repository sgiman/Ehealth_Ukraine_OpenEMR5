/****************************************
 *  qualification.js
 *  Party
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
'use strict';
function SpecialistQualificationViewModel() {
    var self = this;
    self.type = ko.observable().extend({ required: true });
    self.institution_name = ko.observable().extend({ required: true });
    self.speciality = ko.observable().extend({ required: true });
    self.issued_date = ko.observable();
    self.certificate_number = ko.observable();
    self.valid_to = ko.observable();
    self.additional_info = ko.observable();

    self.getNeededValues = function () {
        var temp = JSON.parse(ko.toJSON(self));
        return temp;
    };
}