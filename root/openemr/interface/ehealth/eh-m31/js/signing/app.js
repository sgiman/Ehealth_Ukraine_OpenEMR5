/*********************************************************
 *  app.js
 *  Sign Legal Entity 
 *   
 *  E-health sub-module 3.1   
 *  LEGAL ENTITY TYPE (V2)
 *
 *  OpenEMR     
 *  http://www.open-emr.org
 *
 * -------------------------------------------
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *********************************************************/
var g_euSign = null;
var g_caAddress = "acskidd.gov.ua";  //"ca.iit.com.ua";
var g_caPort = "80";
var g_isNeedReadNewKey = false;

var libType = EndUserLibraryLoader.LIBRARY_TYPE_DEFAULT;
var objId = "euSign";
var langCode = EndUserLibraryLoader.EU_DEFAULT_LANG;

// Privatbank specific errors
var PRIVATBANK_KEYFILE_EXT = '.jks';
var ERR_PRIVATBANK_GET_KEYLIST = 'Помилка під час отриманя списку ключів ПРИВАТ';
var ERR_PRIVATBANK_EMPTY_CONTAINER = 'Помилка зчитування ключового контейнеру ПРИВАТ. Контайнет пустий.';
var ERR_PRIVATBANK_KEYFILE_READING = 'Помилка зчитування ключового файлу ПРИВАТ';
var ERR_PRIVATBANK_PRIVATEKEY_READING = 'Помилка зчитування особистого ключа ПРИВАТ';
var ERR_PRIVATBANK_GET_CERT = 'Виникла помилка під час отриманя інформації про сертифікат ПРИВАТ';
var ERR_PRIVATBANK_GET_CERT_INFO = 'Виникла помилка при отримані інформації про сертифікат ПРИВАТ';

var EU_ERROR_NONE = 0x0000;
var EU_WARNING_END_OF_ENUM = 0x0007;

var settingsForCA = { CAsServers: null, CAServer: null, offline: false, useCMP: null, loadPKCertsFromFile: null };

//================================================================================

//--------------------------------
//             isPrivatBankKeyStore
//--------------------------------
function isPrivatBankKeyStore(fileName) {
	//var dotIndex = fileName.lastIndexOf('.');
	if (fileName.length > 4) {
		var t = fileName.substring(fileName.length - 4).toLowerCase();
		if (t === PRIVATBANK_KEYFILE_EXT.toLowerCase())
			return true;
		else
			return false;
	} else
		return false;
}

//--------------------------------
//             getJSKKeyAliases
//--------------------------------
function getJSKKeyAliases(container) {
	var index = 0;
	var response = "a";
	var KeyAliasArr = [];
	//    var fileName = $('#divForKeyInFile input:file[name=Fichier1]')[0].files[0].name;
	while (response) {
		try {
			response = g_euSign.EnumJKSPrivateKeys(container, index); //, onSuccess, onError
			if (response)
				KeyAliasArr.push(response);
		}
		catch (e) {
			console.log(e);
		}
		index++;
	}
	return KeyAliasArr;
}

//--------------------------------
//                getJSKKeyByAlias
//--------------------------------
function getJSKKeyByAlias(container, keyAlias) {
	return g_euSign.GetJKSPrivateKey(container, keyAlias);
}

//--------------------------------
//                  setCASettings
//--------------------------------
function setCASettings(caIndex, onSuccess, onError) {
	log("Set library settings...");

	if (caIndex && caIndex < 0 || !caIndex)
		caIndex = 0;

	var caServer = settingsForCA.CAsServers && caIndex < settingsForCA.CAsServers.length ?
		settingsForCA.CAsServers[caIndex] : null;
	var offline = ((caServer === null) ||
		(caServer.address === "")) ?
		true : false;
	var useCMP = (!offline && (caServer.cmpAddress !== ""));
	var loadPKCertsFromFile = (caServer === null) ||
		(!useCMP && !caServer.certsInKey);

	settingsForCA.CAServer = caServer;
	settingsForCA.offline = offline;
	settingsForCA.useCMP = useCMP;
	settingsForCA.loadPKCertsFromFile = loadPKCertsFromFile;

	var _onError = function (e) {
		log("Initialize library failed. Error - " + e);
		if (onError) onError(e);
	};

	eu_wait(function (runNext) {
		g_euSign.SetRuntimeParameter(
			g_euSign.EU_SAVE_SETTINGS_PARAMETER,
			g_euSign.EU_SETTINGS_ID_NONE,
			runNext, _onError);
	}).eu_wait(function (runNext) {
		var settings = g_euSign.CreateFileStoreSettings();
		settings.SetPath("");
		settings.SetAutoRefresh(true);
		settings.SetSaveLoadedCerts(true);
		settings.SetExpireTime(3600);
		settings.SetCheckCRLs(false);
		settings.SetOwnCRLsOnly(false);
		settings.SetAutoDownloadCRLs(false);
		settings.SetFullAndDeltaCRLs(false);

		g_euSign.SetFileStoreSettings(
			settings, runNext, _onError);
	}).eu_wait(function (runNext) {
		var tempFlag = true;
		// console.log("Proxy settings:");
		try {
			var sysProxySettings = g_euSign.GetSystemProxySettings();
			//			console.log("UseProxy : " + sysProxySettings.GetUseProxy() 
			//                + "\nAddress : " + sysProxySettings.GetAddress() 
			//                + "\nPort : " + sysProxySettings.GetPort()
			//                + "\nUser : " + sysProxySettings.GetUser());
		} catch (e) {
			console.log("Fail getting proxy settings");
			tempFlag = false;
		}
		if (tempFlag && sysProxySettings.GetUseProxy()) {
			g_euSign.SetProxySettings(
				sysProxySettings, runNext, _onError);
		} else {
			var settings = g_euSign.CreateProxySettings();
			settings.SetUseProxy(false);
			settings.SetAddress("");
			settings.SetPort("");
			settings.SetAnonymous(true);
			settings.SetUser("");
			settings.SetSavePassword(false);
			settings.SetPassword("");
			g_euSign.SetProxySettings(
				settings, runNext, _onError);
		}
	}).eu_wait(function (runNext) {
		var settings = g_euSign.CreateTSPSettings();
		settings.SetGetStamps(true);
		settings.SetAddress(settingsForCA.CAServer.address);
		settings.SetPort(g_caPort);

		g_euSign.SetTSPSettings(
			settings, runNext, _onError);
	}).eu_wait(function (runNext) {
		var settings = g_euSign.CreateOCSPSettings();
		settings.SetUseOCSP(true);
		settings.SetAddress(settingsForCA.CAServer.address);
		settings.SetPort(g_caPort);
		settings.SetBeforeStore(true);

		g_euSign.SetOCSPSettings(
			settings, runNext, _onError);
	}).eu_wait(function (runNext) {
		var settings = g_euSign.CreateLDAPSettings();

		g_euSign.SetLDAPSettings(
			settings, runNext, _onError);
	}).eu_wait(function (runNext) {
		var settings = g_euSign.CreateCMPSettings();
		settings.SetUseCMP(true);
		settings.SetAddress(settingsForCA.CAServer.address);
		settings.SetPort(g_caPort);
		settings.SetCommonName("");

		g_euSign.SetCMPSettings(
			settings, runNext, _onError);
	}).eu_wait(function (runNext) {
		var settings = g_euSign.CreateModeSettings();
		settings.SetOfflineMode(false);
		g_euSign.SetModeSettings(settings,
			runNext, _onError);
	}).eu_wait(function (runNext) {
		log("Settings set");
		if (onSuccess) onSuccess();
	});
}

//--------------------------------
//             LoadDataFromServer
//--------------------------------
function LoadDataFromServer(path, onSuccess, onError, asByteArray) {
	var pThis = this;
	try {
		var httpRequest;
		var url;

		if (XMLHttpRequest)
			httpRequest = new XMLHttpRequest();
		else
			httpRequest = new ActiveXObject("Microsoft.XMLHTTP");

		httpRequest.onload = function () {
			if (httpRequest.readyState != 4)
				return;

			if (httpRequest.status == 200) {
				if (asByteArray) {
					onSuccess(new Uint8Array(this.response));
				} else {
					onSuccess(httpRequest.responseText);
				}
			}
			else {
				onError(pThis.MakeError(EU_ERROR_DOWNLOAD_FILE));
			}
		};

		httpRequest.onerror = function () {
			onError(pThis.MakeError(EU_ERROR_DOWNLOAD_FILE));
		};

		if (path.indexOf('http://') !== 0 &&
			path.indexOf('https://') !== 0) {
			if (!location.origin) {
				location.origin = location.protocol +
					"//" + location.hostname +
					(location.port ? ':' + location.port : '');
			}

			url = location.origin + path;
		} else {
			url = path;
		}

		httpRequest.open("GET", url, true);
		if (asByteArray)
			httpRequest.responseType = 'arraybuffer';
		httpRequest.send();
	} catch (e) {
		onError(pThis.MakeError(EU_ERROR_DOWNLOAD_FILE));
	}
}

var URL_CAS = "/data/CAs.json?version=1.0.16";
// var URL_CAS = "https://iit.com.ua/download/productfiles/CAs.json";

//--------------------------------
//                 loadCAsSettings
//--------------------------------
function loadCAsSettings() {
	var pThis = this;

	var _onError = function (error) {
		NotificationInsteadOfAlert("Помилка", ["Виникла помилка при завантаженні криптографічної бібліотеки"]);
		log(error);
	};

	var _onSuccess = function (casResponse) {
		try {
			var allServers = JSON.parse(casResponse.replace(/\\'/g, "'"));
			var servers = [];

			var select = document.getElementById("CAsServersSelect");
			$("#CAsServersSelect").empty();
			for (var i = 0; i < allServers.length; i++) {
				if (allServers[i]["address"]
					&& allServers[i]["ocspAccessPointAddress"]
					&& allServers[i]["ocspAccessPointPort"]
					&& allServers[i]["cmpAddress"]
					&& allServers[i]["tspAddress"]
					&& allServers[i]["tspAddressPort"]
				) {
					var option = document.createElement("option");
					option.text = allServers[i].issuerCNs[0];
					option.value = i;
					select.add(option);
					servers.push(allServers[i]);
				}
			}

			select.onchange = function () {
				setCASettings(select.selectedIndex);
			};

			settingsForCA.CAsServers = servers;
		} catch (e) {
			_onError(e);
		}
	};

	LoadDataFromServer(URL_CAS, _onSuccess, _onError, false);
}

loadCAsSettings();

//================================================================================

var eu_wait = function (first) {
	return new (function () {
		var self = this;
		var callback = function () {
			var args;
			if (self.deferred.length) {
				args = [].slice.call(arguments);
				args.unshift(callback);
				self.deferred[0].apply(self, args);
				self.deferred.shift();
			}
		};
		this.deferred = [];
		this.eu_wait = function (run) {
			this.deferred.push(run);
			return self;
		};
		first(callback);
	});
};

//--------------------------------------------------------------------------------

function log(msg) {
	console.log(msg);
}

//================================================================================

//--------------------------------
//                loadCryptoLibrary
//--------------------------------
function loadCryptoLibrary(onSuccess, onError) {
	log("Load library...");

	var loader = new EndUserLibraryLoader(libType, objId, langCode);
	//var loader = new EndUserLibraryLoader(EndUserLibraryLoader, "euSign");

	loader.onload = function (library) {
		log("Libary loaded");

		g_euSign = library;
		onSuccess();
	};
	loader.onerror = function (msg, errorCode, libraryOrNull) {
		log("Libary load failed. Error - " + msg);
		$("#notLoadedSignLibText").html(msg);
		$("#notLoadedSignLibModal").modal({ backdrop: "static", keyboard: false });
		$("#SigningModal").modal('hide');
		onError();
	};

	loader.load();
}

//--------------------------------------------------------------------------------

//--------------------------------
//             initializeCryptoLibrary
//--------------------------------
function initializeCryptoLibrary(onSuccess, onError) {
	log("Initialize library...");

	var _onError = function (e) {
		log("Initialize library failed. Error - " + e);
		onError(e);
	};

	eu_wait(function (runNext) {
		g_euSign.SetUIMode(false, runNext, _onError);
	}).eu_wait(function (runNext) {
		g_euSign.Initialize(runNext, _onError);
	}).eu_wait(function (runNext) {
		g_euSign.SetUIMode(false, runNext, _onError);
	}).eu_wait(function (runNext) {
		log("Library initialized");
		onSuccess();
	});
}

//--------------------------------------------------------------------------------

//--------------------------------
//                      getKMTypes
//--------------------------------
function getKMTypes(onSuccess, onError) {
	log("Get key media types");

	var kmTypes = new Array();

	var _getKeyMediaType = function (index) {
		g_euSign.EnumKeyMediaTypes(index,
			function (type) {
				if (type === null || type === '') {
					log("Get key media types. Types - " + kmTypes);

					onSuccess(kmTypes);
					return;
				}

				g_euSign.EnumKeyMediaDevices(index, 0, function (device) {
					if (device === null || device === '') {
						//log("Get key media types. Types - " + kmTypes);
						kmTypes.push("");
						return;
					} else {
						kmTypes.push(type);
					}
				}, function (e) {
					log("Get key media devices failed. Error - " + e);

					onError(e);
				});

				//kmTypes.push(type);
				_getKeyMediaType(index + 1);
			},
			function (e) {
				log("Get key media types failed. Error - " + e);

				onError(e);
			});
	};

	_getKeyMediaType(0);
}

//--------------------------------------------------------------------------------

//--------------------------------
//                 getKMDevices
//--------------------------------
function getKMDevices(typeIndex, onSuccess, onError) {
	log("Get key media devices");

	var kmDevices = new Array();

	var _getKeyMediaDevice = function (typeIndex, deviceIndex) {
		g_euSign.EnumKeyMediaDevices(typeIndex, deviceIndex,
			function (device) {
				if (device === null || device === '') {
					log("Get key media devices. Devices - " + kmDevices);

					onSuccess(kmDevices);
					return;
				}

				kmDevices.push(device);
				_getKeyMediaDevice(typeIndex, deviceIndex + 1);
			},
			function (e) {
				log("Get key media devices failed. Error - " + e);

				onError(e);
			});
	};

	_getKeyMediaDevice(typeIndex, 0);
}

//================================================================================

//--------------------------------
//                 updateKMTypes
//--------------------------------
function updateKMTypes(onSuccess, onError) {
	log("Update key media types...");

	var _onError = function (e) {
		log("Update key media types failed. Error - " + e);

		onError(e);
	};

	eu_wait(function (runNext) {
		getKMTypes(runNext, _onError);
	}).eu_wait(function (runNext, kmTypes) {
		$('#kmTypes').empty();
		var firstValueIndex = -1;
		if (kmTypes.length !== 0) {
			$.each(kmTypes, function (index, value) {
				if (value !== "") {
					if (firstValueIndex === -1 && index > firstValueIndex)
						firstValueIndex = index;
					$('#kmTypes').append(
						$('<option/>', {
							value: index,
							text: value
						}));
				}
			});

			//$('#kmTypes').val(kmTypes[firstValueIndex]);
			if (firstValueIndex !== -1) {
				getKMDevices(firstValueIndex, runNext, _onError);

			} else {
				$('#kmTypes').append($('<option/>', {
					text: 'Жоден носій приватного ключа не був знайдений'
					//,text: value
				}));
				log("Key media types updated");
				$("#kmTypes").prop("disabled", false);
				onSuccess();
			}
		}
	}).eu_wait(function (runNext, kmDevices) {
		$('#kmDevices').empty();

		if (kmDevices.length !== 0) {
			$.each(kmDevices, function (index, value) {
				$('#kmDevices').append(
					$('<option/>', {
						value: value,
						text: value
					}));
			});
			$('#kmDevices').val(kmDevices[0]);
		}
		$("#kmTypes").prop("disabled", false);
		$("#kmDevices").prop("disabled", false);
		log("Key media types updated");

		onSuccess();
	});
}

//--------------------------------------------------------------------------------

//--------------------------------
//              updateKMDevices
//--------------------------------
function updateKMDevices() {
	log("Update key media devices...");

	var _onError = function (e) {
		log("Update key media devices failed. Error - " + e);

		var error = "Виникла помилка при оновленні списку носіїв ключової інформації" +
			(e ? (". " + e) : "");
		if (g_euSign && g_euSign.IsInitialized())
			NotificationInsteadOfAlert("Помилка", [error]);
	};

	eu_wait(function (runNext) {
		getKMDevices(parseInt($('#kmTypes option:selected').val()),
			runNext, _onError);
	}).eu_wait(function (runNext, kmDevices) {
		$('#kmDevices').empty();

		if (kmDevices.length !== 0) {
			$.each(kmDevices, function (index, value) {
				$('#kmDevices').append(
					$('<option/>', {
						value: value,
						text: value
					}));
			});

			$('#kmDevices').val(kmDevices[0]);
		}

		log("Key media devices updated");
	});
}
//--------------------------------------------------------------------------------

//--------------------------------
//                       onLoad
//--------------------------------
function onLoad() {
	var _onError = function (e) {
		var error = "Виникла помилка при завантаженні бібліотеки" +
			(e ? (". " + e) : "");

		if (g_euSign && g_euSign.IsInitialized())
			NotificationInsteadOfAlert("Помилка", [error]);
	};

	eu_wait(function (runNext) {
		loadCryptoLibrary(runNext, _onError);
	}).eu_wait(function (runNext) {
		initializeCryptoLibrary(runNext, _onError);
	}).eu_wait(function (runNext) {
		setCASettings(0, runNext, _onError);
	}).eu_wait(function (runNext) {
		$('#CAsServersSelect').prop('disabled', false);
		$("#kmPassword").prop("disabled", false);
		$("#signFile").prop("disabled", false);
		if ($('input:radio[name=typeOfKey]:checked').val() === 'protectedKey') {
			updateKMTypes(runNext, _onError);
		}
	}).eu_wait(function (runNext) {
		//$('#loadingProtKey').attr("hidden", "true");
		//$("#kmTypes").prop("disabled", false);
		//$("#kmDevices").prop("disabled", false);
		//$("#selectFile").prop("disabled", false);       /// to delete
	});
}

//--------------------------------------------------------------------------------

//--------------------------------
//                     onSignFile
//--------------------------------
function onSignFile(textToSign, successFunction, isArrayBuffer = false, closeAfterSign = true) {   // textToSign  - must to be as ko.toJSON
	if (!textToSign || textToSign === "") {
		console.log("Данні на підпис відсутні.");
		return;
	}
	var kmType = $('#kmTypes option:selected').val();
	var kmDevice = $('#kmDevices').prop('selectedIndex');
	var password = $('#kmPassword').val();
	var typeOfKey = $('input:radio[name=typeOfKey]:checked').val();

	var signedDataTextWithSign = [];
	if (isArrayBuffer)
		signedDataTextWithSign.push(Base64.encode(new TextDecoder("utf-8").decode(textToSign)));
	else
		signedDataTextWithSign.push(Base64.encode(textToSign));

	if (typeOfKey === "protectedKey") {
		if (kmType < 0 || kmDevice < 0) {
			NotificationInsteadOfAlert("Помилка", ["Не обрано носій з особистим ключем"]);
			return;
		}
	}

	if (!password || password === "") {
		NotificationInsteadOfAlert("Помилка", ["Не вказано пароль доступу до носія з особистим ключем"]);
		return;
	}
	var spinner = $('#loading');
	spinner.show();
	var _onError = function (e) {
		spinner.hide();
		var error = "Виникла помилка під час підпису даних. " +
			(e ? (". " + e) : "");
		//$("#myModalForm")[0].reset();
		$('#kmPassword').val("");
		//var _onErrorFin = function (e) {
		//    log("Initialize library failed. Error - " + e);

		//    onError(e);
		//};
		//var final = function () {
		//    log("library closed.");
		//};
		//g_eusign.finalize(final, _onerrorfin);
		//$('#divForKeyInFile input:file[name=Fichier1]').val('');
		NotificationInsteadOfAlert("Помилка", [error]);
	};

	eu_wait(function (runNext) {
		if (g_isNeedReadNewKey) {
			setTimeout(function () {
				runNext();
			}, 1);
			spinner.hide();
			return;
		}
		g_euSign.ResetPrivateKey(runNext, _onError);
	}).eu_wait(function (runNext) {
		g_isNeedReadNewKey = false;
		if ($('input:radio[name=typeOfKey]:checked').val() === 'keyInFile') {
			var files = $('#divForKeyInFile input:file[name=Fichier1]')[0].files;//document.getElementById('PKeyFileInput').files;
			if (files.length !== 1) {
				spinner.hide();
				var error = "Виникла помилка при зчитуванні особистого ключа. " +
					"Опис помилки: файл з особистим ключем не обрано";
				log(error);
				NotificationInsteadOfAlert("Помилка", [error]);
				return;
			}
			var reader = new FileReader();
			var onReaderError = function (name = file.name) {
				NotificationInsteadOfAlert("Помилка", ['Виникли проблеми під час зчитування файлу - ' + name]);
				spinner.hide();
			};
			reader.onerror = onReaderError;
			if (isPrivatBankKeyStore(files[0].name)) {
				reader.onload = function () {
					var arrayBuffer = reader.result;
					//console.log(arrayBuffer.byteLength);
					var Uint8ArrayBuffer = new Uint8Array(arrayBuffer);
					//console.log(Uint8ArrayBuffer);
					var _onFileReadSuccess = function (readedFile) {
						console.log("readed key");
						runNext();
					};
					var KeyAliasArr = getJSKKeyAliases(Uint8ArrayBuffer);
					var selectedAlias;

					var afterSelectingAlias = function (selectedAlias) {
						if (selectedAlias) {
							var keyBuf = getJSKKeyByAlias(Uint8ArrayBuffer, selectedAlias);
							g_euSign.ReadPrivateKeyBinary(keyBuf.privateKey, password, _onFileReadSuccess, _onError);
						}
					};

					if (KeyAliasArr.length <= 0) {
						NotificationInsteadOfAlert("Помилка", ["Відбулася помилка при зчитуванні ключа із розширенням .jks. Можливо він порожній"]);
						spinner.hide();
					} else if (KeyAliasArr.length > 1) {     // more than one key in one container
						spinner.hide();
						var select = $('#JKSAliasSelect');     //document.getElementById("JKSAliasSelect");
						select.empty();
						for (var i = 0; i < KeyAliasArr.length; i++) {
							if (KeyAliasArr[i]) {
								var option = document.createElement("option");
								option.text = KeyAliasArr[i];
								option.value = KeyAliasArr[i];
								select.append(option);
							}
						}
						var JKSAliasSelectButton = document.getElementById("JKSAliasSelectButton");
						JKSAliasSelectButton.onclick = function () {
							var select = document.getElementById("JKSAliasSelect");
							afterSelectingAlias(select.value);
							spinner.show();
						};
						$("#chooseJKSAlias").modal({ backdrop: "static", keyboard: false });
					} else if (KeyAliasArr.length === 1) {
						afterSelectingAlias(KeyAliasArr[0]);
					}
				};
				reader.readAsArrayBuffer(files[0]);
			} else {
				reader.onload = function () {
					var arrayBuffer = reader.result;
					var Uint8ArrayBuffer = new Uint8Array(arrayBuffer);
					var _onFileReadSuccess = function (readedFile) {
						console.log("readed key");
						runNext();
					};
					g_euSign.ReadPrivateKeyBinary(Uint8ArrayBuffer, password, _onFileReadSuccess, _onError);
				};
				reader.readAsArrayBuffer(files[0]);
			}
		} else {
			g_euSign.ReadPrivateKeySilently(
				parseInt(kmType), parseInt(kmDevice),
				password, runNext, _onError);
		}
	}).eu_wait(function (runNext) {
		g_euSign.GetPrivateKeyOwnerInfo(function (word) { log(word); }, function (e) { log(e); spinner.hide();});
		var onSuccess = function (sign) {
			spinner.hide();
			//log(sign);

			signedDataTextWithSign.push(sign);
			model.signedData(signedDataTextWithSign);
			if (successFunction)
				successFunction(sign);
			//console.log(model.signedData());

			$('#SigningModal').modal('hide');
			$("#myModalForm")[0].reset();
			//$('#kmPassword').val("");
			//$('#divForKeyInFile input:file[name=Fichier1]').val('');
			var _onErrorFin = function (e) {
				log("Initialize library failed. Error - " + e);
			};
			var final = function () {
				log("Library closed.");
			};

			if (closeAfterSign)
				g_euSign.Finalize(final, _onErrorFin);
		};
		g_euSign.SignInternal(true, textToSign, onSuccess, _onError);
	});
}

//-----------------------------------
//  SettingsBeforeOpeningModalForSigning
//-----------------------------------
function SettingsBeforeOpeningModalForSigning() {
	$("#SigningModal").modal({ backdrop: "static", keyboard: false });
	if ($('input:radio[name=typeOfKey]:checked').val() === 'keyInFile') {
		$('#divForProtectedKey').attr("hidden", "true");
		$('#divForKeyInFile').removeAttr('hidden');
	}
	else if ($('input:radio[name=typeOfKey]:checked').val() === 'protectedKey') {
		$('#divForKeyInFile').attr("hidden", "true");
		$('#divForProtectedKey').removeAttr('hidden');
		var ans = g_euSign.IsInitialized();
		if (g_euSign && g_euSign.IsInitialized())
			updateKMTypes(function (e) { console.log(e); }, function (e) {
				var error = "Виникла помилка при оновленні списку захищенних носіїв." +
					(e ? (". " + e) : "");
				NotificationInsteadOfAlert("Помилка", [error]);
			});
	}
	$('input:radio[name=typeOfKey]').change(function () {
		if (this.value === 'keyInFile') {
			$('#divForProtectedKey').attr("hidden", "true");
			$('#divForKeyInFile').removeAttr('hidden');
		}
		else if (this.value === 'protectedKey') {
			$('#kmTypes').empty();
			$('#kmDevices').empty();
			$("#kmTypes").prop("disabled", true);
			$("#kmDevices").prop("disabled", true);
			$('#divForKeyInFile').attr("hidden", "true");
			$('#divForProtectedKey').removeAttr('hidden');
			var ans = g_euSign.IsInitialized();
			if (g_euSign && g_euSign.IsInitialized())
				updateKMTypes(function (e) { console.log(e); }, function (e) {
					var error = "Виникла помилка при оновленні списку захищенних носіїв." +
						(e ? (". " + e) : "");
					NotificationInsteadOfAlert("Помилка", [error]);
				});
		}
	});
	$('#CAsServersSelect').attr("disabled", "true");
	$('#kmTypes').empty();
	$('#kmTypes').attr("disabled", "true");
	$('#kmDevices').empty();
	$('#kmDevices').attr("disabled", "true");
	$("#kmPassword").prop("disabled", "true");
	$('#signFile').attr("disabled", "true");

	//$('#loadingProtKey').attr("hidden", "true");
	onLoad();
	$("#keyInFile").prop("checked", true);
	$("#SigningModal").modal({ backdrop: "static", keyboard: false });
	if ($('input:radio[name=typeOfKey]:checked').val() === 'keyInFile') {
		$('#divForProtectedKey').attr("hidden", "true");
		$('#divForKeyInFile').removeAttr('hidden');
	}
	else if ($('input:radio[name=typeOfKey]:checked').val() === 'protectedKey') {
		$('#divForKeyInFile').attr("hidden", "true");
		$('#divForProtectedKey').removeAttr('hidden');
	}
}