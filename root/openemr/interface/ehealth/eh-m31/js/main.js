/**********************************************************
 *  main.js
 *  Main
 *
 *  E-health sub-module 3.1   
 *  LEGAL ENTITY TYPE (V2)
 *
 *  OpenEMR     
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
var wasUserNotifiedAboutErrors = false;
var spinner = $('#loading');

//----------------------------------
// Check Errors In Response and Get Data
//----------------------------------
function CheckErrorsInResponseAndGetData(response, funcName) {
	console.log("------------------------------------------");
	//console.log("*******  RESPONSE: " +  response);
	//console.log("*******  appData: " + response.appData);
	console.log("*******  CheckErrorsInResponseAndGetData / hasError: " + response.hasError);
	console.log("*******  CheckErrorsInResponseAndGetData / hasData: " + response.hasData);
	console.log("*******  CheckErrorsInResponseAndGetData / error: " + response.error);
	console.log("------------------------------------------");
	
	if (response.hasError) {
		if (response.error.statusCode == 401 || response.error.statusCode == 403) {
			var onClosed = function () {
				//window.location.href = "/home/logout";
				//window.location.href = "/openemr/interface/ehealth/auth.php";     
				window.location.href = "/openemr/interface/ehealth/Login.php";     
			};
			NotificationInsteadOfAlert("Помилка", ["Скоріш за все ваша сессія застаріла. Спробуйте оновити сторінку або перезайти у кабінет."], onClosed);
		}
		if (response.hasData) {
			try {
				var parsedAppData = JSON.parse(response.appData);
				console.log(parsedAppData.message);
				if (parsedAppData.message.indexOf("HTTP/1.1 403 Forbidden") > -1) {
					if (!wasUserNotifiedAboutErrors) {
						NotificationInsteadOfAlert("Помилка", ["Скоріш за все ваша сессія застаріла. Спробуйте оновити сторінку або перезайти у кабінет."]);
						wasUserNotifiedAboutErrors = true;
					}
				}
				NotificationInsteadOfAlert("Помилка", [parsedAppData.message]);
			}
			catch(e){
				// console.log(response.appData);
				if (!wasUserNotifiedAboutErrors) {
					NotificationInsteadOfAlert("Помилка", ["Скоріш за все ваша сессія застаріла. Перезайдіть у кабінет."]);
					wasUserNotifiedAboutErrors = true;
				}
			}
		}
		return false;
	}
	
	try  {
		return JSON.parse(response.appData);
	}
	
	catch (e) {
		if (funcName)
			console.log("error was found in " + funcName + " method");
		// console.log(response);
		return false;
	}
	
}

function sortArrayOfPairs(array) {
	array.sort(function (left, right) {
		return left.text.toLowerCase().localeCompare(right.text.toLowerCase());
	});
}

function sortArrayOfOnlyValues(array) {
	array.sort(function (left, right) {
		return left.toLowerCase().localeCompare(right.toLowerCase());
	});
}

//----------------------------------
//     Get Needed Text List with Errors
//----------------------------------
function GetNeededTextListWithErrors(allData) {
	var errorList = [];

	if (allData.error.invalid && allData.error.invalid.length !== 0) {
		var data = allData.error.invalid;
		for (var key in data) {
			if (data.hasOwnProperty(key)) {
				var tempText = data[key].entry;
				var dataRule = data[key].rules;
				for (var keyRule in dataRule) {
					if (dataRule.hasOwnProperty(keyRule)) {
						tempText += (" - " + dataRule[keyRule].description);
					}
				}
				errorList.push(tempText);
			}
		}
		errorList.push('&nbsp;');
		errorList.push("-------Повна відповідь від eHealth---------");
		errorList.push('&nbsp;');
	}
	errorList.push(allData.error.message + "\n");

	return errorList;
}

//----------------------------------
//         Notification in stead of Alert
//----------------------------------
function NotificationInsteadOfAlert(title, textList, functionOnClosed) {
	$("#NotificationModal #NotificationTitle").html(title);

	if (textList.length === 1) {
		$("#NotificationModal #NotificationBodyForm").attr("hidden", "true");
		$("#NotificationModal #NotificationBodySpan").removeAttr('hidden');
		$("#NotificationModal #NotificationBodySpan").html(textList[0]);
	} else {
		$("#NotificationModal #NotificationBodySpan").attr("hidden", "true");
		$("#NotificationModal #NotificationBodyForm").removeAttr('hidden');

		$("#NotificationModal #NotificationTextList").html("");
		for (var index in textList) {
			var ppp = document.createElement("p");
			ppp.innerHTML = textList[index];
			$("#NotificationModal #NotificationTextList").append(ppp);
		}
	}

	$("#NotificationModal").modal({ backdrop: "static", keyboard: false });
	$('#NotificationModal').on('hidden.bs.modal', function (e) {
		if (functionOnClosed)
			functionOnClosed();
	});
}

//----------------------------------
// Notification about Error During Process
//----------------------------------
var LoadingProcess_text = "завантаження";
var DeletingProcess_text = "видалення";
var CreatingOrUpdatingProcess_text = "створення/редагування";
var Dictionaries_text = "допоміжних довідників";

function NotificationAboutErrorDuringProcess(process_text, errorDuringProcessWithWhat, xhr, thrownError, extraLines) {
	var list = [];
	list.push("Під час " + process_text + " " + errorDuringProcessWithWhat + " відбулася помилка.");
	if (xhr) {
		list.push("Статус - " + xhr.status);
		list.push(xhr.responseText);
	}
	if (thrownError) list.push("Помилка - " + thrownError);
	if (extraLines)
		list.push.apply(list, extraLines);
	NotificationInsteadOfAlert("Помилка", list);
}

function returnIfDataExist(source) {
	return (source === false || source) ? source : (function () { return; })();
}

function setValueIfDataExist(fromWhere, toWhere, nameOfVar) {
	var value = fromWhere[nameOfVar]();
	if (value === false || value) {
		toWhere[nameOfVar] = value;
	}
}

function setNumberValFromStrIfDataExist(object, nameOfVar) {
	if (object && nameOfVar && object[nameOfVar])
		object[nameOfVar] = Number(object[nameOfVar]);
}

function fixPhoneNumber(phone) {
	return phone ? phone.replace(/[-()\s]/g, '') : (function () { return; })();
}

function initListOfKoObjects(koList, initList, className) {
	if (!initList) return;
	koList.removeAll();
	for (var f = 0; f < initList.length; f++) {
		var tempObjectForInit = new className();
		tempObjectForInit.initValues(initList[f]);
		koList.push(tempObjectForInit);
	}
}

function getListOfObjectsFromKoList(koList) {
	if (!koList()) return;
	var tempList = [];
	koList().forEach(function (object) {
		var tempObjectForGet = object.getNeededValues();
		if (ko.toJSON(tempObjectForGet) !== "{}")
			tempList.push(tempObjectForGet);
	});
	if (tempList.length === 0)
		tempList = (function () { return; })();
	return tempList;
}

//--------------------------
//               getCookie
//--------------------------
function getCookie(name) {
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if (cookie.length > 0) {
		offset = cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if (end == -1) {
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return(setStr);
}

//--------------------------
//                 get_cookie
//--------------------------
function get_cookie ( cookie_name )
{
  var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
 
  if ( results )
    return ( unescape ( results[2] ) );
  else
    return null;
}

/*
function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
*/

//--------------------------
//                 set_cookie
//--------------------------
function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
  var cookie_string = name + "=" + escape ( value );
 
  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }
 
  if ( path )
        cookie_string += "; path=" + escape ( path );
 
  if ( domain )
        cookie_string += "; domain=" + escape ( domain );
  
  if ( secure )
        cookie_string += "; secure";
  
  document.cookie = cookie_string;
}

//---------------------
//             Load File 
//---------------------
function loadFile(filePath) {
	var result = null;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", filePath, false);
	xmlhttp.send();
	if (xmlhttp.status==200) {
		result = xmlhttp.responseText;
	}
	return result;
}
