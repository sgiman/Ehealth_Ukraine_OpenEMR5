/**********************************************************
 *   agreementModal.js
 *   Згода на обробку персональних даних
 *
 *   OpenEMR      
 *   http://www.open-emr.org
 *
 *   API EHEALTH version 1.0                          
 *   Writing by sgiman, 2020
**********************************************************/
function AgreementViemModal(headerText, textOfAgreement, textNearCheckbox, functionName) {
    var self = this;

	self.headerText = ko.observable(headerText);
	self.textOfAgreement = ko.observable(textOfAgreement);
	self.acceptAgreement = ko.observable(false);
	self.textNearCheckbox = ko.observable(textNearCheckbox);
	self.functionName = functionName;

	self.initValues = function (headerText, textOfAgreement, textNearCheckbox, functionName) {
		self.headerText(headerText);
		self.textOfAgreement(textOfAgreement);
		self.acceptAgreement(false);
		self.textNearCheckbox(textNearCheckbox);
		self.functionName = functionName;
	};

	self.openAgreementModalWindow = function () {
		self.acceptAgreement(false);
		$("#AgreementModal").modal({ backdrop: "static", keyboard: false });
	};
	self.hideAgreementModalWindow = function () {
		$("#AgreementModal").modal('hide');
	};
}