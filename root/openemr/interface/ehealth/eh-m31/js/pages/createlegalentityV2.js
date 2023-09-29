/**********************************************************
 *  createlegalentityV2.js
 *  Create Legal Entity V2
 *
 *  E-health sub-module 3.1.2   
 *  LEGAL ENTITY TYPE (V2)
 *
 *  OpenEMR     
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
////////////////////////////////////////////////////
//                         Create Legal Entity                        //
////////////////////////////////////////////////////
function CreateLegalEntityViewModel(org_id) {
	var self = this;
	var server = "/openemr/interface/ehealth";
	var spinner = $('#loading');

	self.legal_entity = ko.observable(new LegalEntityViewModel());
    
	if (org_id) {
		setTimeout(function () { self.legal_entity().initLegalEntity(); }, 1000);
       //self.legal_entity().initLegalEntity();
       self.legal_entity().legalEntityTypes = self.legal_entity().legalEntityTypesV1;
    } else {
        self.legal_entity().legalEntityTypes = self.legal_entity().legalEntityTypesV2;
    }
	
    self.editable = ko.observable(org_id ? false : true);
	self.allow_edit = function () {
        self.editable(true);
        self.legal_entity().legalEntityTypes(self.legal_entity().legalEntityTypesV2());
	};
	
	self.show_edit_button = function () {
		return !self.editable();
	};
	
	self.disable_input = function () {
		return !self.editable();
	};
	
	self.show_button = function () {
		return self.editable();
	};

	self.FormatDataForSending = function () {
		return self.legal_entity().getNeededValues();
	};

	self.showAgreement = function () {
		if (self.errors().length !== 0) {
		    $('div.alert-danger').show();
		    self.errors.showAllMessages();
		    return;
		}
		$('div.alert-danger').hide();
		self.agreementModal().openAgreementModalWindow();
	};

	self.acceptAgreement = ko.observable(false);
	self.openSigningModal = function () {
		self.agreementModal().hideAgreementModalWindow();
		SettingsBeforeOpeningModalForSigning();
	};
	
	self.onclickSignFile = function () {
		var legadEntityData = ko.toJSON(self.FormatDataForSending());
		onSignFile(legadEntityData, self.createLegalEntity);
	};
	self.agreementModal = ko.observable();
	self.agreementModal(new AgreementViemModal("Умови використання", self.legal_entity().public_offer().consent_text(), "Погоджуюсь з умовами використання", self.openSigningModal));

	//***********************************************
	//        Create or Update Legal Entity
	//***********************************************
	self.createLegalEntity = function () {
		if (self.signedData && self.signedData()) {
			if (self.signedData().length !== 2) {
				console.log("Length of signedData is not 2");
				return;
			}
			////console.log(self.signedData());////
			
			var reqBody = ko.toJS(self.signedData());
			spinner.show();
		
			$.ajax({
				//url: "/api/LegalEntityData/CreateOrUpdateLegalEntity",
				url: server + "/api/LegalEntityData/CreateOrUpdateLegalEntity.php",
				type: "POST",
				data: { signedData: reqBody },
				
				success: function (response) {
					var result = CheckErrorsInResponseAndGetData(response);
					if (!result)
						return;
					if (result.data) {
						var onClosed = function () {
							// window.location.href = "/";     
							window.location.href = "/openemr/interface/ehealth/eh-m31/Login.php";     
						};
						NotificationInsteadOfAlert("Успішне виконання", ["Заклад було створено."], onClosed);
					}
					else if (result.meta.code == 500) {
						NotificationInsteadOfAlert("Помилка", ["На стороні серверу трапилась якась помилка."]);
					}
					else if (result.error) {
						NotificationInsteadOfAlert("У заповнених Вами данних були виявлені наступні помилки:", GetNeededTextListWithErrors(result));
					} else {
						NotificationAboutErrorDuringProcess(CreatingOrUpdatingProcess_text, "закладу", xhr, thrownError);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					NotificationAboutErrorDuringProcess(CreatingOrUpdatingProcess_text, "закладу", xhr, thrownError);
				},
				complete: function () {
					spinner.hide();
				}
			});			
		}
	};

	self.showDataInConsole = function () {
		 console.log(ko.toJSON(self.FormatDataForSending())); // *********************************** //
	};

	self.signedData = ko.observableArray([]);		// [0]  -  data, [1]  -  signed data
	self.errors = ko.validation.group(this);			// need to be in the end of file
}
