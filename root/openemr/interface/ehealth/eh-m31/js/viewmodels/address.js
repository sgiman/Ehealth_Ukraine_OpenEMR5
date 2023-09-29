/****************************************
 *  address.js
 *  Адреса розташування 
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 * 	Writing by sgiman, 2020
 *****************************************/
function AddressViewModel(index, init__Type) {
    var self = this;
	var server = "/openemr/interface/ehealth";
	
    self.dataListID = ko.observable("street_list_" + index);       	//  to make different datalists for streets

    self.type = ko.observable().extend({ required: true });
    self.country = "UA";																//ko.observable();  // maybe will be changed (only  UKRAINE) !!!!
    self.area = ko.observable().extend({ required: true });			//.extend({ deferred: true });
    self.region = ko.observable().extend({ deferred: true });

    self.settlement = ko.observable();
    self.settlement_type = ko.observable().extend({ deferred: true, required: true });
    self.settlement_id = ko.observable().extend({ required: true });
    self.street_type = ko.observable().extend({ required: true });
    self.street = ko.observable().extend({ required: true });
    self.building = ko.observable().extend({ required: true });
    self.apartment = ko.observable();
    self.zip = ko.observable().extend({ required: true, checkReg: '^[0-9]{5}$' });

    // --- Type ---
	if (init__Type) {   	// if many addresses - it's able to set needed type of address
        function setTypeValue(init__Type) {
            setTimeout(function () {
                if (self.type() && self.type() === init__Type)
                { }
                else {
                    self.type(init__Type);
                    setTypeValue(init__Type);
                }
            }, 2000);
        }
        setTypeValue(init__Type);
    }

    self.initValues = function (initAddress) {
        if (initAddress) {
            self.allowSubscribeForClearingDataAfterOptionChanged(false);
            self.building(initAddress.building);
            self.apartment(initAddress.apartment);
            self.zip(initAddress.zip);
            self.country = initAddress.country;

			// -- Area ---
            function checkIfAreaLoaded(initArea) {
                setTimeout(function () {
                    if (self.area()) { }
                    else {
                        self.area(initArea);
                        checkIfAreaLoaded(initArea);
                    }
                }, 2000);
            }
            checkIfAreaLoaded(initAddress.area);

			// --- Area, Region ---
            if (initAddress.region) {
                function checkIfRegionLoaded(initArea, initRegion) {
                    setTimeout(function () {
                        if (self.region()) { }
                        else {
                            var setRegionForReadAndEdit = function () {
                                setTimeout(function () { self.selectedRegion(initRegion); }, 1000);
                                checkIfRegionLoaded(initArea, initRegion);
                            };
                            self.loadRegionsOnClick(initArea, setRegionForReadAndEdit);
                        }
                    }, 2000);
                }
                checkIfRegionLoaded(initAddress.area, initAddress.region);
            } else {
                self.selectedRegion(null);
                self.regions([]);
            }

			// --- Area, Region, Settlement_type, Settlement_id ---
            function checkIfSettlementLoaded(initArea, initRegion, initSettlement_type, initSettlement_id) {
                setTimeout(function () {
                    if (self.settlement_id()) { }
                    else {
                        var setSettlementForReadAndEdit = function () {
                            setTimeout(function () { self.settlement_id(initSettlement_id); }, 1000);
                            checkIfSettlementLoaded(initArea, initRegion, initSettlement_type, initSettlement_id);
                        };
                        self.loadSettlementsOnClick(initArea, initRegion, initSettlement_type, setSettlementForReadAndEdit);
                    }
                }, 2000);

            }
            checkIfSettlementLoaded(initAddress.area, initAddress.region, initAddress.settlement_type, initAddress.settlement_id);

            var setStreetForReadAndEdit = function () {
                setTimeout(function () { self.street(initAddress.street); }, 2000);
            };
            self.loadStreetsOnClick(initAddress.settlement_id, initAddress.street_type, setStreetForReadAndEdit);

			// --- Type, Settlement_type, Street_type ---
            var isDictLoaded = false;
            function checkIfDictLoaded(initType, initSettlement_type, initStreet_type) {
                setTimeout(function () {
                    if (self.type() && self.settlement_type() && self.street_type() && self.type() === initType && self.settlement_type() === initSettlement_type && self.street_type() === initStreet_type)
                        isDictLoaded = true;
                    else {
                        self.type(initType);
                        self.settlement_type(initSettlement_type);
                        self.street_type(initStreet_type);
                        checkIfDictLoaded(initType, initSettlement_type, initStreet_type);
                    }
                }, 2000);
            }
            checkIfDictLoaded(initAddress.type, initAddress.settlement_type, initAddress.street_type);

            function checkIfSpecificDataLoaded() {
                if (self.area() && self.settlement_id() && self.settlements().length > 0 && self.street() && isDictLoaded)
                    self.allowSubscribeForClearingDataAfterOptionChanged(true);
                else
                    setTimeout(function () { checkIfSpecificDataLoaded(); }, 2000);
            }
            checkIfSpecificDataLoaded();
        }
    };
    
	self.getNeededValues = function () {
        function returnIfDataExist(source) {        // except false values
            return (source === false || source) ? source : (function () { return; })();
        }
        var t = {};
        t.country = returnIfDataExist(self.country);
        t.type = returnIfDataExist(self.type());
        t.region = returnIfDataExist(self.region());
        t.area = returnIfDataExist(self.area());
        t.settlement_id = returnIfDataExist(self.settlement_id());
        self.settlements().forEach(function (object) {
            if (object.value === t.settlement_id)
                t.settlement = object.text;
        });
        t.settlement_type = returnIfDataExist(self.settlement_type());
        t.street = returnIfDataExist(self.street());
        t.street_type = returnIfDataExist(self.street_type());
        t.building = returnIfDataExist(self.building());
        t.apartment = returnIfDataExist(self.apartment());
        t.zip = returnIfDataExist(self.zip());
        return t;
    };

    self.areaOfRegions = ko.observable();
    self.loadRegionsOnClick = function (area, setRegionForReadAndEdit) {
        if (area && setRegionForReadAndEdit) {
            self.getRegions(area, setRegionForReadAndEdit);
        } else {
            if (self.area() && self.area() !== self.areaOfRegions())
                self.getRegions(self.area());
        }
    };
    
	//============================
	//      Districts: regionName (UaAddresses)
	//============================
	self.regions = ko.observableArray([]);                												// districts
    self.getRegions = function (regionName, setRegionForReadAndEdit) {             	// districts  (smaller) /   regionName
        //console.log("****** regionName =  " + regionName);
		var request = $.ajax({
            //url: "/api/UaAddresses/Districts/?regionName=" + regionName,
            url: server + "/api/UaAddresses/Districts.php?regionName=" + regionName,
            type: "GET",
            datatype: "json"
        });
        $("#loading").fadeIn(200);
        request.done(function (data) {
            self.regions.removeAll();
            var options = [];
            if (data.data) {
                if (data.data.length === 0) {
                    //options = ['Варіанти відсутні'];
                    console.log('Райони не були знайдені.');
                } else {
                    self.areaOfRegions(data.data[0].region);
                    for (var i = 0; i < data.data.length; i++) {
                        options.push(data.data[i].name);
                    }
                    self.sortArrayOfOnlyValues(options);
                    options.unshift('Не обраний');
                }
            }
            self.regions.push.apply(self.regions, options);
            if (setRegionForReadAndEdit)
                setRegionForReadAndEdit();
            $("#loading").fadeOut(10);
        });
        request.fail(function (xhr) {
            $("#loading").fadeOut(10);
            console.log(xhr.responseText);
        });
    };

    self.selectedRegion = ko.computed({             // district
        read: function () {
            return self.region();
        },
        write: function (region) {
            if (region) {
                if (region === "Не обраний" || region === "Райони не були знайдені.")
                    self.region("");
                else {
                    self.region(region);
                }
            }
        }
    });

	//============================
	//               Regions (UaAddresses)
	//============================
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
    self.getRegionsOfCountry();

	//============================
	//              Settlement (UaAddresses)
	//============================
    self.areaOfSettlement = ko.observable();
    self.regionOfSettlement = ko.observable();
    self.settlementTypeOfSettlement = ko.observable(self.settlement_type());
    self.loadSettlementsOnClick = function (area, region, settlement_type, setSettlementForReadAndEdit) {
        if (area && settlement_type && setSettlementForReadAndEdit) {
            self.getSettlements(region, area, settlement_type, setSettlementForReadAndEdit);
        } else {
            if (self.area())
                if (self.area() !== self.areaOfSettlement() || self.region() !== self.regionOfSettlement() || self.settlement_type() !== self.settlementTypeOfSettlement() || self.settlements().length === 0)
                    self.getSettlements(self.region(), self.area(), self.settlement_type());
        }
    };
    self.settlements = ko.observableArray([]);             																		 	// towns
    self.getSettlements = function (region, area, settlement_type, setSettlementForReadAndEdit) {			// district   (smaller) / region
        if (!area)
            return;
        self.areaOfSettlement(area);
        self.regionOfSettlement((region === 'Оберіть населений пункт') ? null : region);
        self.settlementTypeOfSettlement(settlement_type);
        
		if (!region)
            region = "";
        $("#loading").fadeIn(200);
        
		var request = $.ajax({
            //url: "/api/UaAddresses/Settlements?region=" + area + "&district=" + region,
            url: server + "/api/UaAddresses/Settlements.php?region=" + area + "&district=" + region,
            type: "GET",
            datatype: "json"
        });

        request.done(function (data) {
            self.settlements.removeAll();
            var options = [];
            if (data.data) {
                if (data.data && data.data.length === 0) {
                    options = [{ value: null, text: 'Населені пункти обраного типу відсутні' }];
                    self.settlement_id(null);
                } else {
                    for (var i = 0; i < data.data.length; i++) {
                        if (data.data[i].type === settlement_type)
                            options.push({ value: data.data[i].id, text: data.data[i].name });
                    }
                    if (options.length === 0) {
                        options = [{ value: null, text: 'Населені пункти обраного типу відсутні' }];
                        self.settlement_id(null);
                    }
                    else {
                        self.sortArrayOfPairs(options);
                        options.unshift({ value: null, text: 'Оберіть населений пункт' });
                    }
                }
            }
            self.settlements.push.apply(self.settlements, options);
            if (setSettlementForReadAndEdit)
                setSettlementForReadAndEdit();
            $("#loading").fadeOut(10);
        });
        request.fail(function (xhr) {
            console.log(xhr.responseText);
            $("#loading").fadeOut(10);
        });
    };

	//============================
	//                 Streets (UaAddresses)
	//============================
    self.settlementIdOfStreet = ko.observable();
    self.streetTypeOfStreet = ko.observable(self.street_type());
    self.loadStreetsOnClick = function (settlementId, streetType, setStreetForReadAndEdit) {
        if (settlementId && streetType && setStreetForReadAndEdit) {
            self.getStreets(settlementId, streetType, setStreetForReadAndEdit);
        } else {
            if (self.settlement_id())
                if (self.settlement_id() !== self.settlementIdOfStreet() || self.street_type() !== self.streetTypeOfStreet() || self.streets().length === 0)
                    self.getStreets(self.settlement_id(), self.street_type());
        }
    };

    self.streets = ko.observableArray([]);
    self.getStreets = function (settlementId, streetType, setStreetForReadAndEdit) {
        self.settlementIdOfStreet(settlementId);
        self.streetTypeOfStreet(streetType);
        $("#loading").fadeIn(200);
        var request = $.ajax({
            //url: "/api/UaAddresses/Streets/?settlementId=" + settlementId + "&streetType=" + streetType,
            url: server + "/api/UaAddresses/Streets.php?settlementId=" + settlementId + "&streetType=" + streetType,
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.streets.removeAll();
            var options = [];       //  optionsText: 'text'
            if (data) {
                for (var i = 0; i < data.length; i++) {
                    options.push({ value: data[i].name });           // value: data.streets[i].id, 
                }
            }
            if (options.length === 0)
                options = [{ value: 'Варіанти відсутні' }];
            else
                self.sortArrayOfValues(options);
            self.streets.push.apply(self.streets, options);
            $("#loading").fadeOut(10);
            if (setStreetForReadAndEdit)
                setStreetForReadAndEdit();
        });
        request.fail(function (xhr) {
            console.log(xhr.responseText);
            $("#loading").fadeOut(10);
        });
    };

/*******************************************************************/
    // BLOCKED
	self.disabled_region = function() {
        return self.area() === 'М.КИЇВ'
            || self.area() === 'М.СЕВАСТОПОЛЬ';
    };
/*********************************************************************
	// NO BLOCKED
	self.disabled_region = function() {
		//if (self.area() === 'М.КИЇВ'  || self.area() === 'М.СЕВАСТОПОЛЬ') 
		return  false;
	}
/*********************************************************************/
  
	//============================
	//                     Subscribe (clear)
	//============================
    self.allowSubscribeForClearingDataAfterOptionChanged = ko.observable(true);
    self.area.subscribe(function (area) {
        if (self.allowSubscribeForClearingDataAfterOptionChanged()) {
            self.region('');
            self.regions([]);
            self.settlements([]);
            if (self.street()) self.street("");
            self.streets([]);
        }
    });
    self.region.subscribe(function (region) {
        if (self.allowSubscribeForClearingDataAfterOptionChanged()) {
            self.settlements([]);
            if (self.street()) self.street("");
            self.streets([]);
        }
    });
    self.settlement_type.subscribe(function (settlement_type) {
        if (self.allowSubscribeForClearingDataAfterOptionChanged() && settlement_type) {
            self.settlements([]);
            if (self.street()) self.street("");
            self.streets([]);
        }
    });
    self.settlement_id.subscribe(function (settlement_id) {
        if (self.allowSubscribeForClearingDataAfterOptionChanged()) {
            self.streets([]);
            if (self.street()) self.street("");
        }
        if (self.settlement_id() && self.street_type()) {
            //self.getStreets(self.settlement_id(), self.street_type());
        }
    });
    self.street_type.subscribe(function (street_type) {
        if (self.allowSubscribeForClearingDataAfterOptionChanged() && street_type) {
            self.streets([]);
            if (self.street()) self.street("");
        }
        if (street_type && self.settlement_id()) {
            //self.getStreets(self.settlement_id(), self.street_type());
        }
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

    self.sortArrayOfOnlyValues = function (array) {
        array.sort(function (left, right) {
            return left.toLowerCase().localeCompare(right.toLowerCase());
        });
    };
}
