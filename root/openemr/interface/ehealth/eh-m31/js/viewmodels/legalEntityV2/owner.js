/**********************************************************
 * 	owner.js
 * 	Керівник (власник)
 *
 * 	Main e-health submodule 3.1.2   
 * 	LEGAL ENTITY TYPE (V2)
 *
 * 	OpenEMR     
 *		http://www.open-emr.org
 *
 *  	API EHEALTH version 1.0                          
 * 	Writing by sgiman, 2020
**********************************************************/
function OwnerViewModel() {
    var self = this;

	self.employee_id = ko.observable();
	self.first_name = ko.observable().trimmed().extend({ required: true });
	self.last_name = ko.observable().trimmed().extend({ required: true });
	self.second_name = ko.observable().trimmed();
    self.tax_id = ko.observable().extend({ required: true, checkReg: '^[1-9][0-9]{9}$' });
    self.birth_date = ko.observable().extend({ required: true });
    self.gender = ko.observable().extend({ required: true });
	self.email = ko.observable().trimmed().extend({ required: true, email: true });
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

	self.initValues = function (initData) {
		if (jQuery && jQuery.isEmptyObject(initData)) return;
		
		self.employee_id(initData.employee_id);
		self.first_name(initData.first_name);
		self.last_name(initData.last_name);
		self.second_name(initData.second_name);
		self.tax_id(initData.tax_id);
		self.birth_date(initData.birth_date);
		setTimeout(function () { self.gender(initData.gender); }, 2000);
		//self.gender(initData.gender);
		
		self.email(initData.email);
		setTimeout(function () { self.position(initData.position); }, 2000);
		//self.position(initData.position);

		initListOfKoObjects(self.phones, initData.phones, PhoneViewModel);
		initListOfKoObjects(self.documents, initData.documents, DocumentViewModel);
	};
	self.getNeededValues = function () {
		var temp = {};

		//temp.employee_id = returnIfDataExist(self.employee_id());
		temp.first_name = returnIfDataExist(self.first_name());
		temp.last_name = returnIfDataExist(self.last_name());
		temp.second_name = returnIfDataExist(self.second_name());
		temp.tax_id = returnIfDataExist(self.tax_id());
		temp.birth_date = returnIfDataExist(self.birth_date());
		temp.gender = returnIfDataExist(self.gender());
		temp.email = returnIfDataExist(self.email());
		temp.position = returnIfDataExist(self.position());

		temp.phones = getListOfObjectsFromKoList(self.phones);
		temp.documents = getListOfObjectsFromKoList(self.documents);

		return temp;
	};
}