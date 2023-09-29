/****************************************
 *  division_read.js
 *  Division Read
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
function DivisionReadViewModel() {
	var server = "/openemr/interface/ehealth";
    var self = this;

    self.name = ko.observable();
    self.type = ko.observable();
    self.email = ko.observable();
    self.adresses = ko.observableArray([]);
    self.location = ko.observableArray([]);
    self.phones = ko.observableArray([]);
    self.status = ko.observable();
    self.id = ko.observable();
    self.external_id = ko.observable();
    self.href = ko.computed(function () {
        //return  server +'/Cabinet/Division/' + self.id();
        return  server + '/eh-m31/Division.php?id=' + self.id();
    });

	//-----------------------------------
    //            PhoneTypes (Dictionaries)
	//-----------------------------------
    self.phoneTypes = ko.observableArray([]);
    self.getPhoneTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/PhoneTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.phoneTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            self.sortArrayOfPairs(options);
            self.phoneTypes.push.apply(self.phoneTypes, options);
        });
    };
    self.getPhoneTypes();

	//-----------------------------------
    //            DivisionTypes (Dictionaries)
	//-----------------------------------
    self.divisionTypes = ko.observableArray([]);
    self.getDivisionTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/DivisionTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.divisionTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            self.sortArrayOfPairs(options);
            self.divisionTypes.push.apply(self.divisionTypes, options);
        });
    };
    self.getDivisionTypes();

    self.getDivisionTypeName = function() {
        for(var i = 0; i < self.divisionTypes().length; i++) {
            if (self.type() === self.divisionTypes()[i].value) {
                return self.divisionTypes()[i].text;
            }
        }
        return '';
    };

	//-----------------------------------
    //           AddressTypes (Dictionaries)
	//-----------------------------------
    self.addressTypes = ko.observableArray([]);
    self.getAddressTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/AddressTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.addressTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    if (data.values[key].code == "RESIDENCE")               //  for divisions it's able to use only residence type
                        options.unshift({ value: data.values[key].code, text: data.values[key].name });
                    // else options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            self.sortArrayOfPairs(options);
            self.addressTypes.push.apply(self.addressTypes, options);
        });
    };
    self.getAddressTypes();

	//-----------------------------------
    //               Regions (UaAddresses)
	//-----------------------------------
    self.regionsOfCountry = ko.observableArray([]);
    self.getRegionsOfCountry = function () {
        var request = $.ajax({
            url: server + "/api/UaAddresses/Regions.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.regionsOfCountry.removeAll();
            var options = [];
            for (var i = 0; i < data.data.length; i++) {
                options.push({ value: data.data[i].id, text: data.data[i].name });
            }
            self.sortArrayOfPairs(options);
            self.regionsOfCountry.push.apply(self.regionsOfCountry, options);
        });
    };
    //self.getRegionsOfCountry();
    
	//-----------------------------------
    //          SettlementTypes (Dictionaries)
	//-----------------------------------
    self.settlementTypes = ko.observableArray([]);
    self.getSettlementTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/SettlementTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.settlementTypes.removeAll();
            var options = [];
            var tempPair;
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    if (data.values[key].code.toLowerCase() == 'city')
                        tempPair = { value: data.values[key].code, text: data.values[key].name };
                    else
                        options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            self.sortArrayOfPairs(options);
            // to make city as first option
            options.unshift(tempPair);
            self.settlementTypes.push.apply(self.settlementTypes, options);
        });
    };
    self.getSettlementTypes();

	//-----------------------------------
    //             StreetTypes (Dictionaries)
	//-----------------------------------
    self.streetTypes = ko.observableArray([]);
    self.getStreetTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/StreetTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.streetTypes.removeAll();
            var options = [];
            var tempPair;
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    if (data.values[key].code.toLowerCase() == 'street')
                        tempPair = { value: data.values[key].code, text: data.values[key].name };
                    else
                        options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            self.sortArrayOfPairs(options);
            // to make street as first option
            options.unshift(tempPair);
            self.streetTypes.push.apply(self.streetTypes, options);
        });
    };
    self.getStreetTypes();

    self.addresses = ko.observableArray([new AddressReadViewModel()]);           //  self.getDistricts, self.getSettlements, self.getStreets

    self.sortArrayOfPairs = function (array) {
        array.sort(function (left, right) {
            return left.text.toLowerCase().localeCompare(right.text.toLowerCase());
        });
    };

    self.viewDivision = function() {
        window.location.href = self.href();
    }

}