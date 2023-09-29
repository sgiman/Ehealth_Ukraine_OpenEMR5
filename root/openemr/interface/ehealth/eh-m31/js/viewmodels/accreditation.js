/****************************************
 *  accreditation.js
 *  Акредитація 
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
function AccreditationViewModel() {
    var self = this;

    self.category = ko.observable().extend({ required: true });

	self.order_no = ko.observable().extend({
		required: {
			onlyIf: function () { return self.category() !== "NO_ACCREDITATION"; }
		}
	});

	self.issued_date = ko.observable();
    self.expiry_date = ko.observable();
    self.order_date = ko.observable();

	self.initValues = function (initData) {
		if (initData) {
			setTimeout(function () { self.category(initData.category); }, 2000);
			//self.category(initData.category);			
			self.expiry_date(initData.expiry_date);
			self.issued_date(initData.issued_date);
			self.order_date(initData.order_date);
			self.order_no(initData.order_no);
		} else self.category("NO_ACCREDITATION");
	};

	self.getNeededValues = function () {
		var temp = {};
		if (self.category() !== "NO_ACCREDITATION") {
			temp.category = returnIfDataExist(self.category());
			temp.issued_date = returnIfDataExist(self.issued_date());
			temp.expiry_date = returnIfDataExist(self.expiry_date());
			temp.order_no = returnIfDataExist(self.order_no());
			temp.order_date = returnIfDataExist(self.order_date());
		} else
			temp = (function () { return; })();

		return temp;
	};
}