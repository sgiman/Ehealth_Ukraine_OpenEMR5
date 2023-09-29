/****************************************
 *  education.js
 *  Education
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
'use strict';
function SpecialistEducationViewModel() {
    var self = this;
    self.country = ko.observable().extend({ required: true });
    self.city = ko.observable().extend({ required: true });
    self.institution_name = ko.observable().extend({ required: true });
    self.issued_date = ko.observable();
    self.diploma_number = ko.observable().extend({ required: true });
    self.degree = ko.observable().extend({ required: true });
    self.speciality = ko.observable().extend({ required: true });

    self.getNeededValues = function () {
        var temp = JSON.parse(ko.toJSON(self));
        return temp;
    };
}