/**********************************************************
 *  location.js
 *  Location
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function LocationViewModel() {
    var self = this;
    
    self.latitude = ko.observable().extend({ required: true, checkReg: '^([1-8]?[1-9]|[1-9]0)\\.{1}\\d{1,6}$' });
    self.longitude = ko.observable().extend({ required: true, checkReg: '^([1-8]?[1-9]|[1-9]0)\\.{1}\\d{1,6}$' });
}
