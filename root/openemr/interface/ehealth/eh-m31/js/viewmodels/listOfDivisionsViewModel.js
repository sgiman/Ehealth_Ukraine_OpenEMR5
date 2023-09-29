/**********************************************************
 *  listOfDivisionsViewModel.js
 *  List of Divisions
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function ListOfDivisionsViewModel() {
	var self = this;
   	var server = "/openemr/interface/ehealth";
	var spinner = $('#loading');

    //-------------------------
	//          Get Division List
	//-------------------------
	self.division = ko.observable(new DivisionReadViewModel());

    self.divisions = ko.observableArray([]);
    self.getDivisions = function (onSuccess, org_id, id) {
        // var org_id = document.getElementById("org_id").value;
		if (org_id) {
			spinner.show();
            $.ajax({
                url: server + "/api/LegalEntityData/GetDivisionList.php",
                type: "POST",
				data: { org_id: org_id },
				success: function (response) {
					var result = CheckErrorsInResponseAndGetData(response);
					if (!result)
						return;
                    if (result && result.data) {
                        var data = result.data;
                        self.divisions.removeAll();
                        for (var d = 0; d < data.length; d++) {
                            var item = data[d];
                            var division = new DivisionReadViewModel();
                            division.id(item.id);
                            division.name(item.name);
                            division.status(item.status);
                            division.type(item.type);
                            division.email(item.email);

                            // var div_location = new LocationViewModel();
                            // if (item.location) {
                            //     div_location.latitude(item.location.latitude);
                            //     div_location.longitude(item.location.longitude);
                            // }
                            // division.location(div_location);
                            division.location = item.location;

                            division.addresses.removeAll();
                            for (var a = 0; a < item.addresses.length; a++) {
                                var addresses = new AddressReadViewModel();
                                addresses.type(item.addresses[a]["type"]);
                                addresses.area(item.addresses[a]["area"]);
                                addresses.region(item.addresses[a]["region"]);
                                addresses.settlement(item.addresses[a]["settlement"]);
                                addresses.settlement_type(item.addresses[a]["settlement_type"]);
                                addresses.settlement_id(item.addresses[a]["settlement_id"]);
                                addresses.street_type(item.addresses[a]["street_type"]);
                                addresses.street(item.addresses[a]["street"]);
                                addresses.building(item.addresses[a]["building"]);
                                addresses.apartment(item.addresses[a]["apartment"]);
                                addresses.zip(item.addresses[a]["zip"]);
                                division.addresses.push(addresses);
                            }

                            division.phones.removeAll();
                            for (var f = 0; f < item.phones.length; f++) {
                                var phone = new PhoneViewModel();
                                phone.type(item.phones[f].type);
                                phone.number(item.phones[f].number);
                                division.phones.push(phone);
                            }

                            if (id === division.id())
                                self.division(division);

                            // console.log(JSON.stringify(division,null,2));
                            self.divisions.push(division);
                        }
                        // console.log(JSON.stringify(self.division(),null,2));
                        onSuccess(self.divisions());
                    }
                },
				error: function (xhr, ajaxOptions, thrownError) {
					NotificationAboutErrorDuringProcess(LoadingProcess_text, "списку підрозділів", xhr, thrownError);
				},
				complete: function () {
					spinner.hide();
				}
            });
        }
    };

    self.convertStatus = function (status) {
        if (status === "ACTIVE")
            return "Відкрите";
        else
            return "Закрите";
    };

    self.openResults = function (org_id, id) {
        // if (document.getElementById("datatable-hide-columns").classList.contains("hide")) {
        //     var active = document.querySelector("#datatable-hide-columns");
        //     active.classList.remove("hide");
        // }
        self.divisions.removeAll();
		var onSuccess = function (data) {
			if (data.length === 0) {
				console.log("Підрозділи відсутні");     // instead of asked variants will be text 'no result'
			}
		};
        self.getDivisions(onSuccess, org_id, id);
    };
}