/**********************************************************
 *  security.js
 *  Get Redirect Uri
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function SecurityViewModel() {
    var self = this;

    // var link = 'https://62.64.127.68'; 	//pre-prod
    // var link = 'https://93.183.206.83'; 	//prod
    
	//--------------------------
	//            Get Redirect Uri
	//--------------------------
	self.redirect_uri = ko.observable().extend({ required: true });
    self.GetRedirectUri = function () {
        var request = $.ajax({
            //url: "/api/LegalEntityData/GetRedirectUri",
            url: "/openemr/interface/ehealth/api/LegalEntityData/GetRedirectUri.php",
            type: "GET",
            datatype: "json"
        });
			
		request.done(function (response) {
			var data = CheckErrorsInResponseAndGetData(response);
			//console.log("****** DATA: " + data["data"]);
			
			if (!data || !data["data"]) {
				NotificationAboutErrorDuringProcess(LoadingProcess_text, Dictionaries_text);
				return;
			}
            self.redirect_uri(data["data"]);
		});
		
		request.fail(function (xhr, textStatus, errorThrown) {
			NotificationAboutErrorDuringProcess(LoadingProcess_text, Dictionaries_text, xhr, errorThrown);
		});
		
    };
	
    self.GetRedirectUri();
}