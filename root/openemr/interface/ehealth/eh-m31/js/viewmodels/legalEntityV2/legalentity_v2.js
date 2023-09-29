/**********************************************************
 *  legalentity_v2.js
 *  Legal Entity v2 (main)
 *
 *  E-health submodule 3.1.2   
 *  LEGAL ENTITY TYPE (V2)
 *
 *  OpenEMR     
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
var  eh_path = "/openemr/interface/ehealth";
var eh_dic_001 = eh_path + "/api/Dictionaries/LEGAL_ENTITY_TYPE_V2.php";
var eh_dic_002 = eh_path + "/api/Dictionaries/LegalEntityTypes.php";
var eh_dic_003 = eh_path + "/api/Dictionaries/LegalForms.php";
var eh_dic_004 = eh_path + "/api/Dictionaries/LICENSE_TYPE.php";
var eh_dic_005 = eh_path + "/api/Dictionaries/AddressTypes.php";
var eh_dic_006 = eh_path + "/api/Dictionaries/SettlementTypes.php"
var eh_dic_007 = eh_path + "/api/Dictionaries/StreetTypes.php";
var eh_dic_008 = eh_path + "/api/Dictionaries/PhoneTypes.php";
var eh_dic_009 = eh_path + "/api/Dictionaries/Genders.php";
var eh_dic_010 = eh_path + "/api/Dictionaries/Positions.php";
var eh_dic_011 = eh_path + "/api/Dictionaries/DocumentTypes.php";
var eh_dic_012 = eh_path + "/api/Dictionaries/AccreditationCategories.php";
var eh_le_013 = eh_path + "/api/LegalEntityData/GetLegalEntityByIdV2.php";
var eh_le_014 = eh_path + "/api/LegalEntityData/GetLegalEntityOwnerV2.php";
var eh_ua_015 = eh_path  + "/api/UaAddresses/Regions.php";

//******************************************
//             LegalEntityViewModel() 
//******************************************
function LegalEntityViewModel() {
    var self = this;

	self.LEName = ko.observable("Профайл закладу");
	self.id = ko.observable();
	
	self.edrpou = ko.observable().extend({ required: true, checkReg: '^[0-9]([0-9]{7}|[0-9]{9})$' });
	self.type = ko.observable().extend({ required: true });

	self.edr = ko.observable(new EdrViewModel());

	self.phones = ko.observableArray([new PhoneViewModel()]).extend({ required: true });
	self.email = ko.observable().trimmed().extend({ required: true, email: true });
    self.website = ko.observable();
    self.receiver_funds_code = ko.observable();
    self.beneficiary = ko.observable();

    // STATUS (INFO ADDITING)
	self.nhs_comment = ko.observable();
    self.nhs_reviewed = ko.observable();
    self.nhs_verified = ko.observable();
    self.edr_verified = ko.observable();
	
	self.status = ko.observable();
	self.type = ko.observable();
	
	//-------------------------------------- 
	// (001) LEGAL_ENTITY_TYPE_V2 (Dictionaries)
	//--------------------------------------
	self.legalEntityTypesV2 = ko.observableArray([]);
    self.getLegalEntityTypesV2 = function () {
        var request = $.ajax({
			//url: eh_path + "/api/Dictionaries/LEGAL_ENTITY_TYPE_V2.php",
			url: eh_dic_001,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
			self.legalEntityTypesV2.removeAll();
            var options = [];
            for (var key in data.values) {
				if (data.values.hasOwnProperty(key)) {
					options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            sortArrayOfPairs(options);
			self.legalEntityTypesV2.push.apply(self.legalEntityTypesV2, options);
        });
    };
	self.getLegalEntityTypesV2();

	//-------------------------------------- 
	//  (002) Legal Entity Types (Dictionaries) 
	//-------------------------------------- 
	self.legalEntityTypesV1 = ko.observableArray([]);
	self.getLegalEntityTypesV1 = function () {
		var request = $.ajax({
			//url: eh_path + "/api/Dictionaries/LegalEntityTypes.php",
			url: eh_dic_002,
			type: "GET",
			datatype: "json"
		});
		request.done(function (data) {
            self.legalEntityTypesV1.removeAll();
			var options = [];
			for (var key in data.values) {
				if (data.values.hasOwnProperty(key)) {
					if (data.values[key].code.toUpperCase() !== "MIS")
						options.push({ value: data.values[key].code, text: data.values[key].name });
				}
			}
			sortArrayOfPairs(options);
            self.legalEntityTypesV1.push.apply(self.legalEntityTypesV1, options);
		});
	};
    self.getLegalEntityTypesV1();
    self.legalEntityTypes = ko.observableArray([]);

	//-------------------------------------- 
	//  (003) Legal Forms (Dictionaries)
	//-------------------------------------- 
	self.legalForms = ko.observableArray([]);
	self.getLegalForms = function () {
		var request = $.ajax({
			//url: eh_path + "/api/Dictionaries/LegalForms.php",
			url: eh_dic_003,
			type: "GET",
			datatype: "json"
		});
		request.done(function (data) {
			self.legalForms.removeAll();
			var options = [];
			for (var key in data.values) {
				if (data.values.hasOwnProperty(key)) {
					options.push({ value: data.values[key].code, text: data.values[key].name });
				}
			}
			sortArrayOfPairs(options);
			self.legalForms.push.apply(self.legalForms, options);
		});
	};
	self.getLegalForms();

	//-------------------------------------- 
	// (004) LICENSE_TYPE (Dictionaries) 
	//-------------------------------------- 
	self.licenseTypes = ko.observableArray([]);
	self.getLicenseTypes = function () {
		var request = $.ajax({
			//url: eh_path +  "/api/Dictionaries/LICENSE_TYPE.php",
			url: eh_dic_004,
			type: "GET",
			datatype: "json"
		});
		request.done(function (data) {
			self.licenseTypes.removeAll();
			var options = [];
			for (var key in data.values) {
				if (data.values.hasOwnProperty(key)) {
					options.push({ value: data.values[key].code, text: data.values[key].name });
				}
			}
			sortArrayOfPairs(options);
			self.licenseTypes.push.apply(self.licenseTypes, options);
		});
	};
	self.getLicenseTypes();

	//-------------------------------------- 
    //  (005) Address Types (Dictionaries)
	//-------------------------------------- 
	self.addressTypes = ko.observableArray([]);
    self.getAddressTypes = function () {
        var request = $.ajax({
            //url: eh_path + "/api/Dictionaries/AddressTypes.php",
            url: eh_dic_005,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.addressTypes.removeAll();
            var options = [];
            for (var key in data.values) {
				if (data.values.hasOwnProperty(key)) {
					options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            sortArrayOfPairs(options);
            self.addressTypes.push.apply(self.addressTypes, options);
        });
    };
    self.getAddressTypes();

	//-------------------------------------- 
    //  (006) Settlement Types (Dictionaries) 
	//-------------------------------------- 
	self.settlementTypes = ko.observableArray([]);    
	self.getSettlementTypes = function () {
        var request = $.ajax({
            //url: eh_path + "/api/Dictionaries/SettlementTypes.php",
            url: eh_dic_006,
            type: "GET",
            datatype: "json"
        });		
        request.done(function (data) {
            self.settlementTypes.removeAll();
            var options = [];
            var tempPair;
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    if (data.values[key].code.toLowerCase() === 'city')
                        tempPair = { value: data.values[key].code, text: data.values[key].name };
                    else
                        options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }			
            sortArrayOfPairs(options);
            // to make city as first option
            options.unshift(tempPair);
            self.settlementTypes.push.apply(self.settlementTypes, options);
        });
    };
    self.getSettlementTypes();

	//-------------------------------------- 
    // (007) Street Types (Dictionaries) 
	//-------------------------------------- 
	self.streetTypes = ko.observableArray([]);
    
	self.getStreetTypes = function () {
        var request = $.ajax({
            //url: eh_path +  "/api/Dictionaries/StreetTypes.php",
            url: eh_dic_007,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.streetTypes.removeAll();
            var options = [];
            var tempPair;
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    if (data.values[key].code.toLowerCase() === 'street')
                        tempPair = { value: data.values[key].code, text: data.values[key].name };
                    else
                        options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            sortArrayOfPairs(options);
            // to make street as first option
            options.unshift(tempPair);
            self.streetTypes.push.apply(self.streetTypes, options);
        });
    };
    self.getStreetTypes();

	//-----------------
	// residence_address
	//-----------------
	self.residence_address = ko.observable(new AddressViewModel(1, "RESIDENCE")).extend({ required: true });
	 
	self.addPhone = function () {
		self.phones.push(new PhoneViewModel());
	};
	self.removePhone = function (phone) {
		self.phones.remove(phone);
	};
	
	//-------------------------------------- 
	// (008) Phone Types (Dictionaries)
	//-------------------------------------- 
	self.phoneTypes = ko.observableArray([]);
		self.getPhoneTypes = function () 
		{
		
		var request = $.ajax({
			//url: eh_path + "/api/Dictionaries/PhoneTypes.php",
			url: eh_dic_008,
			type: "GET",
			datatype: "json"
		});
		
		request.done(function (data) {
			self.phoneTypes.removeAll();
			//console.log(data.values);
			var options = [];
			for (var key in data.values) {
				if (data.values.hasOwnProperty(key)) {
					options.push({ value: data.values[key].code, text: data.values[key].name });
				}
			}
			sortArrayOfPairs(options);
			self.phoneTypes.push.apply(self.phoneTypes, options);
		});
	};
	
	self.getPhoneTypes();
    self.owner = ko.observable(new OwnerViewModel()).extend({ required: true });

	//-------------------------------------- 
    // (009) Genders (Dictionaries)
	//-------------------------------------- 
	self.genders = ko.observableArray([]);
    self.getGenders = function () {
        
		var request = $.ajax({
            //url: eh_path +  "/api/Dictionaries/Genders.php",
            url: eh_dic_009,
            type: "GET",
            datatype: "json"
        });
		
        request.done(function (data) {
            self.genders.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            sortArrayOfPairs(options);
            self.genders.push.apply(self.genders, options); // ?????
        });
    };
    self.getGenders();

	//-------------------------------------- 
    // (010) Positions (Dictionaries)
	//-------------------------------------- 
	self.positions = ko.observableArray([]);
    self.getPositions = function () {
        var request = $.ajax({
            //url: eh_path + "/api/Dictionaries/Positions",
            url: eh_dic_010,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.positions.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            sortArrayOfPairs(options);
            self.positions.push.apply(self.positions, options);
        });
    };
    self.getPositions();

	//-------------------------------------- 
    // (011) Document Types (Dictionaries) 
	//-------------------------------------- 
	self.documentTypes = ko.observableArray([]);
    self.getDocumentTypes = function () {
        var request = $.ajax({
            //url: eh_path + "/api/Dictionaries/DocumentTypes.php",
            url: eh_dic_011,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.documentTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            sortArrayOfPairs(options);
            self.documentTypes.push.apply(self.documentTypes, options);
        });
    };
    self.getDocumentTypes();

	self.accreditation = ko.observable(new AccreditationViewModel());
	self.license = ko.observable(new LicenseViewModel()).extend({ required: true });

	//-------------------------------------- 
    // (012) Accreditation Categories (Dictionaries) 
	//-------------------------------------- 
	self.accreditationCategories = ko.observableArray([]);
    self.getAccreditationCategories = function () {
        var request = $.ajax({
            //url: eh_path + "/api/Dictionaries/AccreditationCategories.php",
            url: eh_dic_012,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.accreditationCategories.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            sortArrayOfPairs(options);
            self.accreditationCategories.push.apply(self.accreditationCategories, options);
        });
    };
    self.getAccreditationCategories();

    self.security = ko.observable(new SecurityViewModel()).extend({ required: true });
    self.public_offer = ko.observable(new PublicOfferViewModel()).extend({ required: true });

    self.owner_name = ko.pureComputed(function () {
        return (self.owner().last_name() ? (self.owner().last_name() + ' ') : '')
            + (self.owner().first_name() ? (self.owner().first_name() + ' ') : '')
            + (self.owner().second_name() ? self.owner().second_name() : '');
    });
    self.owner_position = ko.pureComputed(function () {
        for (var i = 0; i < self.positions().length; i++) {
            var item = self.positions()[i];
            if (item.value === self.owner().position())
                return item.text;
        }
        return '';
    });
  
	//*******************************************************
	//  (013) Get Legal Entity by ID V2 (LegalEntityData) 
	//*******************************************************
	self.initLegalEntity = function () {

		var edr_verified_name;
		var nhs_reviewed_name;
		var nhs_verified_name;

		$("#loading").fadeIn(200);
		self.initLegalEntityOwnerData();
        $.ajax({
			//url: eh_path + "/api/LegalEntityData/GetLegalEntityByIdV2",
			url: eh_le_013,
            type: "GET",
			success: function (response) {
				var result = CheckErrorsInResponseAndGetData(response);
				
				if (!result)
					return;
				
				if (result.data) {
					var data = result.data;
					// console.log(data);
					console.log('********* LegalEntityByIdV2 / beneficiary = ', data.beneficiary); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					console.log('********* LegalEntityByIdV2 / nhs_comment = ', data.nhs_comment); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					console.log('********* LegalEntityByIdV2 / nhs_reviewed = ', data.nhs_reviewed); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					console.log('********* LegalEntityByIdV2 / nhs_verified = ', data.nhs_verified); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					console.log('********* LegalEntityByIdV2 / edr_verified = ', data.edr.edr_verified); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					console.log('********* LegalEntityByIdV2 / status = ', data.status); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					console.log('********* LegalEntityByIdV2 / type = ', data.type); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                    self.LEName(data.edr ? data.edr.name : self.LEName());
					self.id(data.id);

					setTimeout(function () { self.type(data['type']); }, 2000);
                    //self.type(data['type']);

					self.edrpou(data.edrpou);
					self.edr().initValues(data.edr);
					self.beneficiary(data.beneficiary);

					//======== S T A T U S ========
					
					self.nhs_comment(data.nhs_comment);
				
					// edr_verified_name
					if (data.edr_verified != null)  
						edr_verified_name = 'ВЕРИФІКОВАНО';
					else
						edr_verified_name = 'НЕВЕРИФІКОВАНО';
					
					// nhs_reviewed_name
					if (data.nhs_reviewed) 
						nhs_reviewed_name = 'РОЗГЛЯНУТО';
					else 
						nhs_reviewed_name = 'НЕРОЗГЛЯНУТО';
					
					// nhs_verified_name
					if (data.nhs_verified)
						nhs_verified_name = 'ВЕРИФІКОВАНО';
					else 
						nhs_verified_name = 'НЕВЕРИФІКОВАНО';

					self.edr_verified(edr_verified_name);
					self.nhs_reviewed(nhs_reviewed_name);
					self.nhs_verified(nhs_verified_name);
					
					self.status(data.status);
					self.type(data.type);
					//========================

					if (data.email) self.email(data.email);
					if (data.website) self.website(data.website);
					self.receiver_funds_code(data.receiver_funds_code);
					
					self.residence_address().initValues(data.residence_address);
					initListOfKoObjects(self.phones, data.phones, PhoneViewModel);

					self.accreditation().initValues(data.accreditation);
					setTimeout(function () { self.license().initValues(data.license); }, 2000);
					//self.license().initValues(data.license);
					
					if (data.security)
						self.security(data.security);

					if (data.public_offer)
						self.public_offer(data.public_offer);
				} else if (result.meta.code == 500) {
					NotificationInsteadOfAlert("Помилка", ["На стороні серверу трапилась якась помилка."]);
				} else if (result.error) {
					NotificationInsteadOfAlert("Були виявлені наступні помилки:", GetNeededTextListWithErrors(result));
				} else {
					NotificationAboutErrorDuringProcess(LoadingProcess_text, "даних про заклад");
				}
            },
			error: function (xhr, ajaxOptions, thrownError) {
				NotificationAboutErrorDuringProcess(LoadingProcess_text, "даних про заклад", xhr, thrownError);
			},
			complete: function () {
				$("#loading").fadeOut(10);
			}
        });

	};
	
	//********************************************************
	// (014) Get Legal Entity Owner V2 (LegalEntityData) 
	//********************************************************
	self.initLegalEntityOwnerData = function () {
		$("#loading").fadeIn(200);
		$.ajax({
			//url: eh_path + "/api/LegalEntityData/GetLegalEntityOwnerV2.php",
			url: eh_le_014,
			type: "GET",
			success: function (response) {
				var result = CheckErrorsInResponseAndGetData(response);
				if (!result)
					return;
				if (jQuery && !jQuery.isEmptyObject(result.data) || !jQuery && result.data) {
					var data = result.data;
					// console.log(data);
					self.owner().initValues(data);
				} else if (result.meta.code == 500) {
					NotificationInsteadOfAlert("Помилка", ["На стороні серверу трапилась якась помилка."]);
				} else if (result.error) {
					NotificationInsteadOfAlert("Були виявлені наступні помилки:", GetNeededTextListWithErrors(result));
				} else {
					var extraLines = [];
					if (result.meta.code == 200 && jQuery && jQuery.isEmptyObject(result.data))
						extraLines.push("Дані про керівника відсутні.");
					NotificationAboutErrorDuringProcess(LoadingProcess_text, "даних про керівника закладу", null, null, extraLines);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				NotificationAboutErrorDuringProcess(LoadingProcess_text, "даних про керівника закладу", xhr, thrownError);
			},
			complete: function () {
				$("#loading").fadeOut(10);
			}
		});

	};

	//----------------------------------------
    //              (015) Regions (UaAddresses)
	//----------------------------------------
	self.regionsOfCountry = ko.observableArray([]);
    self.getRegionsOfCountry = function () {
        var request = $.ajax({
            //url: eh_path + "/api/UaAddresses/Regions.php",
            url: eh_ua_015,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.regionsOfCountry.removeAll();
            var options = [];
            for (var i = 0; i < data.data.length; i++) {
                options.push(data.data[i].name);
            }
            sortArrayOfOnlyValues(options);
            self.regionsOfCountry.push.apply(self.regionsOfCountry, options);
        });
    };
    self.getRegionsOfCountry();


	//****************************************************
    //         Get Needed Values (Legal Entity V2)
	//****************************************************
	self.getNeededValues = function () {
		var temp = {};

		temp.edrpou = returnIfDataExist(self.edrpou());
		temp.type = returnIfDataExist(self.type());
		temp.residence_address = self.residence_address().getNeededValues();
		temp.phones = getListOfObjectsFromKoList(self.phones);
		temp.email = returnIfDataExist(self.email());
		temp.website = returnIfDataExist(self.website());
		temp.receiver_funds_code = returnIfDataExist(self.receiver_funds_code());
		temp.beneficiary = returnIfDataExist(self.beneficiary());
		
		temp.owner = self.owner().getNeededValues();
		temp.accreditation = self.accreditation().getNeededValues();
		temp.license = self.license().getNeededValues();

		temp.security = {};
		temp.security.redirect_uri = self.security().redirect_uri();

		temp.public_offer = {};
		temp.public_offer.consent = returnIfDataExist(self.public_offer().consent());
		temp.public_offer.consent_text = returnIfDataExist(self.public_offer().consent_text());
		return temp;
	};
}
