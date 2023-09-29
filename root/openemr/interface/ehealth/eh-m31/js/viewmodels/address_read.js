/****************************************
 *  address_read.js
 *  Address Read
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
 function AddressReadViewModel() {
	var server = "/openemr/interface/ehealth";
	var self = this;

    self.type = ko.observable().extend({required: true});
    self.country = "UA";   //ko.observable();                            // maybe will be changed (only for UKRAINE)
    self.area = ko.observable().extend({required: true});        //.extend({ deferred: true });
    self.region = ko.observable().extend({deferred: true});

    self.settlement = ko.observable().extend({deferred: true, required: true});
    self.settlement_type = ko.observable().extend({deferred: true, required: true});
    self.settlement_id = ko.observable().extend({required: true});
    self.street_type = ko.observable().extend({required: true});
    self.street = ko.observable().extend({required: true});
    self.building = ko.observable().extend({required: true});
    self.apartment = ko.observable();
    self.zip = ko.observable().extend({required: true, checkReg: '^[0-9]{5}$'});

    self.areaId = ko.observable();
    self.selectedArea = ko.computed(function () {
        return {value: self.areaId(), text: self.area()};
    });


/******************************************************************************************************

	//--------------------------------
    //            Districts (UaAddresses)
	//--------------------------------
    self.regions = ko.observableArray([]);                     // districts
    self.getRegions = function (regionName) {             // districts  (smaller)  /  regionName
        var request = $.ajax({
            url: server + "/api/UaAddresses/Districts.php?regionName=" + regionName,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.regions.removeAll();
            var options = [];
            if (data.data) {
                if (data.data.length == 0) {
                    options = [{value: null, text: 'Варіанти відсутні'}];
                } else {
                    for (var i = 0; i < data.data.length; i++) {
                        options.push({
                            value: data.data[i].id,
                            text: data.data[i].name
                        });
                    }
                    self.sortArrayOfPairs(options);
                    options.unshift({value: null, text: 'Не обраний'});
                }
            }
            self.regions.push.apply(self.regions, options);
        });
    };

    self.regionId = ko.observable();
    self.selectedRegion = ko.computed(function () {
        return {value: self.regionId(), text: self.region()};
    });

	//--------------------------------
    //             Regions (UaAddresses)
	//--------------------------------
    self.regionsOfCountry = ko.observableArray([]);
    self.getRegionsOfCountry = function () {
        var request = $.ajax({
            url: server + "/api/UaAddresses/Regions.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.regionsOfCountry.removeAll();
            //console.log(data);
            var options = [];
            for (var i = 0; i < data.data.length; i++) {
                options.push({value: data.data[i].id, text: data.data[i].name});
            }
            //console.log(options);
            self.regionsOfCountry.push.apply(self.regionsOfCountry, options);
        });
    };
    self.getRegionsOfCountry();


	//--------------------------------
    //         Settlements (UaAddresses)
	//--------------------------------
    self.settlements = ko.observableArray([]);              								// towns
    self.getSettlements = function (region, area, settlement_type) {       	// district   (smaller) / region
        $("#loading").fadeIn(200);

        var request = $.ajax({
            url: server + "/api/UaAddresses/Settlements.php?region=" + area + "&district=" + region,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.settlements.removeAll();
            var options = [];
            if (data.settlements) {
                if (data.settlements && data.data.length == 0) {
                    options = [{value: null, text: 'Варіанти відсутні'}];
                } else {
                    for (var i = 0; i < data.data.length; i++) {
                        if (data.data[i].type == settlement_type)
                            options.push({
                                value: data.data[i].id,
                                text: data.data[i].name
                            });
                    }
                    self.sortArrayOfPairs(options);
                    options.unshift({value: null, text: 'Не обраний'});
                }
            }
            self.settlements.push.apply(self.settlements, options);
            $("#loading").fadeOut(10);
        });
    };

    self.selectedSettlement = ko.computed(function () {
        return {value: self.settlement_id(), text: self.settlement()};
    });

	//--------------------------------
    //             Streets (UaAddresses)
	//--------------------------------
    self.streets = ko.observableArray([]);
    self.getStreets = function (settlementId, streetType) {
        var request = $.ajax({
            url: server + "/api/UaAddresses/Streets.php?settlementId=" + settlementId + "&streetType=" + streetType,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.streets.removeAll();
            var options = [];       //  optionsText: 'text'
            if (data.data) {
                for (var i = 0; i < data.data.length; i++) {
                    options.push({value: data.data[i].name});           // value: data.streets[i].id,
                }
            }
            if (options.length == 0)
                options = [{value: 'Варіанти відсутні'}];
            self.sortArrayOfValues(options);
            self.streets.push.apply(self.streets, options);
        });
    };

    self.selectedStreet = ko.computed(function () {
        if (self.street())
            return self.street();         //  value: self.street_id(),
        else
            return null;
    });

******************************************************************************************************/

    self.addressFull = ko.computed(function () {
        var address_full = "";
        address_full += self.settlement()
            ? self.settlement()
            : "";
        address_full += self.street()
            ? address_full
                ? ", " + self.street()
                : self.street()
            : "";
        address_full += self.building()
            ? address_full
                ? ", " + self.building()
                : self.building()
            : "";
        address_full += self.apartment()
            ? address_full
                ? ", " + self.apartment()
                : self.apartment()
            : "";
        return address_full;
    });

    self.sortArrayOfPairs = function (array) {
        array.sort(function (left, right) {
            return left.text.toLowerCase().localeCompare(right.text.toLowerCase());
        });
    };

    self.sortArrayOfValues = function (array) {
        array.sort(function (left, right) {
            return left.value.toLowerCase().localeCompare(right.value.toLowerCase());
        });
    };
}
