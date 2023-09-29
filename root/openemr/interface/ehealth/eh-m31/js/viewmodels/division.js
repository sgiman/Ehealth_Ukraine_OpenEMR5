/****************************************
 *  division.js
 *  Division
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
function DivisionViewModel() {
    var self = this;
	var server = "/openemr/interface/ehealth";
	
    self.country = "UA";														//ko.observable();  // maybe will be changed (only  UKRAINE) !!!!
	self.id = ko.observable();
    self.name = ko.observable().trimmed().extend({ required: true });
    self.type = ko.observable().extend({ required: true });
    self.email = ko.observable().extend({ required: true, email: true });
    self.location = ko.observableArray();
    self.external_id = ko.observable();
    self.phones = ko.observableArray([new PhoneViewModel()]);
    self.status = ko.observable();

    self.addPhone = function () {
        self.phones.push(new PhoneViewModel());
    };
	
    self.removePhone = function (phone) {
        self.phones.remove(phone);
    };

    //---------------------------
	//    PhoneTypes (Dictionaries)
    //---------------------------
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

    //---------------------------
	//    DivisionTypes (Dictionaries)
    //---------------------------
    self.divisionTypes = ko.observableArray([]);
    self.getDivisionTypes = function () {
        var request = $.ajax({
            url: server +  "/api/Dictionaries/DivisionTypes.php",
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


    //---------------------------
	//    AddressTypes (Dictionaries)
    //---------------------------
    self.addressTypes = ko.observableArray([]);
    self.getAddressTypes = function () {
        var request = $.ajax({
            url: server +  "/api/Dictionaries/AddressTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.addressTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    if (data.values[key].code === "RESIDENCE")               ////  for divisions it's able to use only residence type
                        options.unshift({ value: data.values[key].code, text: data.values[key].name });
                    // else options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            self.sortArrayOfPairs(options);
            self.addressTypes.push.apply(self.addressTypes, options);
        });
    };
    self.getAddressTypes();

    //---------------------------
	//    Regions (UaAddresses)
    //---------------------------
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
                options.push(data.data[i].name);
            }
            sortArrayOfOnlyValues(options);
            self.regionsOfCountry.push.apply(self.regionsOfCountry, options);
        });
    };
    self.getRegionsOfCountry();

    //------------------------------
	//    SettlementTypes (Dictionaries)
    //------------------------------
    self.settlementTypes = ko.observableArray([]);
    self.getSettlementTypes = function () {
        var request = $.ajax({
            url: server +  "/api/Dictionaries/SettlementTypes.php",
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
            self.sortArrayOfPairs(options);
            // to make city as first option
            options.unshift(tempPair);
            self.settlementTypes.push.apply(self.settlementTypes, options);
        });
    };
    self.getSettlementTypes();

    //------------------------------
	//        StreetTypes (Dictionaries)
    //------------------------------
    self.streetTypes = ko.observableArray([]);
    self.getStreetTypes = function () {
        var request = $.ajax({
            url: server +  "/api/Dictionaries/StreetTypes.php",
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
            self.sortArrayOfPairs(options);
            // to make street as first option
            options.unshift(tempPair);
            self.streetTypes.push.apply(self.streetTypes, options);
        });
    };
    self.getStreetTypes();

    self.addresses = ko.observableArray([new AddressViewModel(0)]).extend({ required: true });

    self.addLocation = function () {
        if (self.location().length === 0)
            self.location.push(new LocationViewModel());
    };
    self.removeLocation = function (location) {
        self.location.remove(location);
    };

	self.getNeededValues = function () {
		var temp = {};

		temp.name = returnIfDataExist(self.name());
		temp.type = returnIfDataExist(self.type());
		temp.email = returnIfDataExist(self.email());
		temp.external_id = returnIfDataExist(self.external_id());

		if (self.addresses()) {
			temp.addresses = [];
			self.addresses().forEach(function (object) {
				temp.addresses.push(object.getNeededValues());
			});
		}

		if (self.location()) {
			if (self.location()[0]) {
				temp.location = {};
				temp.location.latitude = parseFloat(self.location()[0].latitude());
				temp.location.longitude = parseFloat(self.location()[0].longitude());
			}
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

    self.sortArrayOfPairs = function (array) {
        array.sort(function (left, right) {
            return left.text.toLowerCase().localeCompare(right.text.toLowerCase());
        });
    };
    self.sortArrayOfOnlyValues = function (array) {
        array.sort(function (left, right) {
            return left.toLowerCase().localeCompare(right.toLowerCase());
        });
    };
}