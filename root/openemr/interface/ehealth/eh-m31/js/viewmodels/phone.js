/**********************************************************
 *  phone.js
 *  Телефон
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function PhoneViewModel() {
    var self = this;
    
    self.type = ko.observable().extend({ required: true });
	self.number = ko.observable().extend({ required: true, mask: "+38(099)999-99-99" });

	self.initValues = function (initData) {
		if (!initData) return;

		setTimeout(function () { self.type(initData.type); }, 4000);
		//self.type(initData.type);

		self.number(initData.number);
	};
	
	self.getNeededValues = function () {
		var temp = {};
		temp.type = returnIfDataExist(self.type());
		temp.number = fixPhoneNumber(returnIfDataExist(self.number()));
		return temp;
	};
}