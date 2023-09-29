/****************************************
 *  speciality.js
 *  Party
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
'use strict';
function SpecialistSpecialityViewModel() {
    var self = this;

    self.speciality = ko.observable().extend({ required: true });
    self.speciality_officio = ko.observable(false).extend({ required: true });
    self.level = ko.observable().extend({ required: true });
    self.qualification_type = ko.observable().extend({ required: true });
    self.attestation_name = ko.observable().extend({ required: true });
    self.attestation_date = ko.observable().extend({ required: true });
    self.valid_to_date = ko.observable();
    self.certificate_number = ko.observable().extend({ required: true });

    self.getNeededValues = function () {
        var temp = JSON.parse(ko.toJSON(self));
        return temp;
    };
}