/**********************************************************
 *  license.js
 *  Ліцензії  
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function LicenseViewModel() {
    var self = this;

    self.license_number = ko.observable().extend({ required: true });
    self.issued_by = ko.observable().extend({ required: true });
    self.issued_date = ko.observable().extend({ required: true });
    self.expiry_date = ko.observable();
    self.active_from_date = ko.observable().extend({ required: true });
    self.what_licensed = ko.observable().extend({ required: true });
    self.order_no = ko.observable().extend({ required: true });
}
