/**********************************************************
 * 	license.js
 * 	Ліцензії
 *
 * 	Main e-health submodule 3.1.2   
 * 	LEGAL ENTITY TYPE (V2)
 *
 * 	OpenEMR     
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 * 	Writing by sgiman, 2020
**********************************************************/
function LicenseViewModel() {
    var self = this;

    self.id = ko.observable();
	self.type = ko.observable().extend({ required: true });
	self.license_number = ko.observable().extend({ required: true });
    self.issued_by = ko.observable().extend({ required: true });
    self.issued_date = ko.observable().extend({ required: true });
    self.expiry_date = ko.observable();
    self.active_from_date = ko.observable().extend({ required: true });
    self.what_licensed = ko.observable();
	self.order_no = ko.observable().extend({ required: true });

	self.initValues = function (initData) {
		if (!initData) return;
		self.id(initData.id);
		self.type(initData.type);
		self.license_number(initData.license_number);
		self.issued_by(initData.issued_by);
		self.issued_date(initData.issued_date);
		self.expiry_date(initData.expiry_date);
		self.active_from_date(initData.active_from_date);
		self.what_licensed(initData.what_licensed);
		self.order_no(initData.order_no);
	};
	self.getNeededValues = function () {
		var temp = {};
		//temp.id = returnIfDataExist(self.id());
		temp.type = returnIfDataExist(self.type());
		temp.license_number = returnIfDataExist(self.license_number());
		temp.issued_by = returnIfDataExist(self.issued_by());
		temp.issued_date = returnIfDataExist(self.issued_date());
		temp.expiry_date = returnIfDataExist(self.expiry_date());
		temp.active_from_date = returnIfDataExist(self.active_from_date());
		temp.what_licensed = returnIfDataExist(self.what_licensed());
		temp.order_no = returnIfDataExist(self.order_no());
		return temp;
	};
}