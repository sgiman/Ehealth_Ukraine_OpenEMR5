/****************************************
 *  party.js
 *  Party
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
'use strict';
function PartyViewModel() {
    var self = this;
	var server = "/openemr/interface/ehealth";

    self.id = ko.observable();
	self.first_name = ko.observable().trimmed().extend({ required: true });
	self.last_name = ko.observable().trimmed().extend({ required: true });
	self.second_name = ko.observable().trimmed();
    self.birth_date = ko.observable().extend({ required: true });
    self.gender = ko.observable().extend({ required: true });
    self.no_tax_id = ko.observable(false);
    self.tax_id = ko.observable().extend({ required: true, check_tax_id: '^[1-9][0-9]{9}$' });
	self.email = ko.observable().trimmed().extend({ required: true, email: true });

	self.documents = ko.observableArray([new DocumentViewModel(self.birth_date)]).extend({ required: true });
    self.phones = ko.observableArray([new PhoneViewModel()]).extend({ required: true });

    //-----------------------------
	//          Genders (Dictionaries)
    //-----------------------------
	self.genders = ko.observableArray([]);
    self.getGenders = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/Genders.php",
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
            //console.log(options);
            self.genders.push.apply(self.genders, options);
        });
    };
    self.getGenders();

    //-----------------------------
	//      PhoneTypes (Dictionaries)
    //-----------------------------
    self.phoneTypes = ko.observableArray([]);
    self.getPhoneTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/PhoneTypes.php",
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
            //console.log(options);
            self.phoneTypes.push.apply(self.phoneTypes, options);
        });
    };
    self.getPhoneTypes();

    //-----------------------------
	//    DocumentTypes (Dictionaries)
    //-----------------------------
    self.documentTypes = ko.observableArray([]);
    self.getDocumentTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/DocumentTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.documentTypes.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.documentTypes.push.apply(self.documentTypes, options);
        });
    };
    self.getDocumentTypes();


    //-----------------------------
	//                   addPhone
    //-----------------------------
    self.addPhone = function () {
        self.phones.push(new PhoneViewModel());
    };
    self.removePhone = function (phone) {
        self.phones.remove(phone);
    };

    //-----------------------------
	//              addDocument
    //-----------------------------
    self.addDocument = function () {
		self.documents.push(new DocumentViewModel(self.birth_date));
    };

    //-----------------------------
	//            removeDocument
    //-----------------------------
    self.removeDocument = function (document) {
        self.documents.remove(document);
    };

    //-----------------------------
	//                    fullName
    //-----------------------------
    self.fullName = ko.computed(function () {
        if (self.last_name() && self.first_name())
            return (self.last_name() + " " + self.first_name() + " " + (self.second_name() ? self.second_name() : ""));
        else
            return "";
    }, self);

    //-----------------------------
	//             getNeededValues
    //-----------------------------
    self.getNeededValues = function () {
        var temp = {};
        temp.first_name = returnIfDataExist(self.first_name());
        temp.last_name = returnIfDataExist(self.last_name());
        temp.second_name = returnIfDataExist(self.second_name());
        temp.birth_date = returnIfDataExist(self.birth_date());
        temp.gender = returnIfDataExist(self.gender());
        temp.no_tax_id = returnIfDataExist(self.no_tax_id());
        temp.tax_id = returnIfDataExist(self.tax_id());
        temp.email = returnIfDataExist(self.email());
		
        if (self.documents()) {
            temp.documents = [];
            self.documents().forEach(function (objectDoc) {
				temp.documents.push(objectDoc.getNeededValues());
            });
            if (jQuery.isEmptyObject(temp.documents))
                temp.documents = (function () { return; })();
        }

        if (self.phones()) {
            temp.phones = [];
            self.phones().forEach(function (object) {
                var tempObject = {};
                tempObject.type = returnIfDataExist(object.type());
                tempObject.number = fixPhoneNumber(returnIfDataExist(object.number()));
                temp.phones.push(tempObject);
            });
            if (jQuery.isEmptyObject(temp.phones))
                temp.phones = (function () { return; })();
        }
        
        return temp;
    };
}
