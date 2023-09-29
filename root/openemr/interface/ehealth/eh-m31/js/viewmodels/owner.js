/**********************************************************
 *  owner.js
 *  Керівник (власник)
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function OwnerViewModel() {
    var self = this;

    self.first_name = ko.observable().extend({ required: true });
    self.last_name = ko.observable().extend({ required: true });
    self.second_name = ko.observable().extend({ required: true });
    self.tax_id = ko.observable().extend({ required: true, checkReg: '^[1-9][0-9]{9}$' });
    self.birth_date = ko.observable().extend({ required: true });
    self.gender = ko.observable().extend({ required: true });
    self.email = ko.observable().extend({ required: true, email: true });
    self.documents = ko.observableArray([new DocumentViewModel(self.birth_date)]).extend({ required: true });
    self.phones = ko.observableArray([new PhoneViewModel()]).extend({ required: true });
    self.position = ko.observable().extend({ required: true });

	self.addPhone = function () {
		self.phones.push(new PhoneViewModel());
	};
	
	self.removePhone = function (phone) {
		self.phones.remove(phone);
	};

	self.addDocument = function () {
		self.documents.push(new DocumentViewModel(self.birth_date));
	};
	
	self.removeDocument = function (document) {
		self.documents.remove(document);
	};

	self.getNeededValues = function () {
		var temp = {};

		temp.first_name = returnIfDataExist(self.first_name());
		temp.last_name = returnIfDataExist(self.last_name());
		temp.second_name = returnIfDataExist(self.second_name());
		temp.tax_id = returnIfDataExist(self.tax_id());
		temp.birth_date = returnIfDataExist(self.birth_date());
		temp.gender = returnIfDataExist(self.gender());
		temp.email = returnIfDataExist(self.email());
		temp.position = returnIfDataExist(self.position());

		if (self.phones()) {
			temp.phones = [];
			self.phones().forEach(function (object) {
				var tempObject = {};
				tempObject.type = returnIfDataExist(object.type());
				tempObject.number = fixPhoneNumber(returnIfDataExist(object.number()));
				if (ko.toJSON(tempObject) !== "{}")
					temp.phones.push(tempObject);
			});
			if (temp.phones.length === 0)
				temp.phones = (function () { return; })();
		}

		if (self.documents()) {
			temp.documents = [];
			self.documents().forEach(function (objectDoc) {
				var tempObject = objectDoc.getNeededValues();
				if (ko.toJSON(tempObject) !== "{}")
					temp.documents.push(tempObject);
			});
			if (temp.documents.length === 0)
				temp.documents = (function () { return; })();
		}

		return temp;
	};
}