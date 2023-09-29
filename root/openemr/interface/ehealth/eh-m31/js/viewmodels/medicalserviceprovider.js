/**********************************************************
 *  medicalserviceprovider.js
 *  Акредитація та ліцензія  
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function MedicalServiceProviderViewModel() {
    var self = this;

    self.accreditation = ko.observable(new AccreditationViewModel());
    self.licenses = ko.observableArray([new LicenseViewModel()]).extend({ required: true });

    self.addLicense = function () {
        self.licenses.push(new LicenseViewModel());
    };
    self.removeLicense = function (license) {
        self.licenses.remove(license);
    };
}