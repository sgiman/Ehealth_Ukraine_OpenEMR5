/**********************************************************
 *  edr.js
 *  ЄДР реквізити
 *
 *  Main e-health submodule 3.1.2   
 *  LEGAL ENTITY TYPE (V2)
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function KvedsFromEdrViewModel() {
	var self = this;

	self.name = ko.observable();
	self.code = ko.observable();
	self.is_primary = ko.observable();

	self.initValues = function (initData) {
		if (!initData) return;
		self.name(initData.name);
		self.code(initData.code);
		self.is_primary(initData.is_primary);
	};
}

function RegistrationAddressFromEdrViewModel() {
	var self = this;

	self.zip = ko.observable();
	self.country = ko.observable();
	self.address = ko.observable();

	self.initValues = function (initData) {
		if (!initData) return;
		self.zip(initData.zip);
		self.country(initData.country);
		self.address(initData.address);
	};
}

function EdrViewModel() {
    var self = this;

	self.id = ko.observable();
	self.name = ko.observable();
	self.short_name = ko.observable();
	self.public_name = ko.observable();
	self.legal_form = ko.observable();
	self.edrpou = ko.observable();
	self.kveds = ko.observableArray([]);
	self.registration_address = ko.observable(new RegistrationAddressFromEdrViewModel());
	self.state = ko.observable();

	self.initValues = function (initData) {
		if (!initData) return;

		self.id(initData.id);
		self.name(initData.name);
		self.short_name(initData.short_name);
		self.public_name(initData.public_name);
		self.legal_form(initData.legal_form);
		self.edrpou(initData.edrpou);
		self.state(initData.state);
		self.registration_address().initValues(initData.registration_address);
		initListOfKoObjects(self.kveds, initData.kveds, KvedsFromEdrViewModel);
	};
}