
//================================================================================

// eustrings.js

//================================================================================
//================================================================================

var EndUserStrings = function() {
};

//================================================================================

EndUserStrings.WEB_LIBRARY_OS_NOT_SUPPORTED = 0x20000;
EndUserStrings.WEB_LIBRARY_NOT_INSTALLED_OR_RUN = 0x20001;
EndUserStrings.WEB_LIBRARY_JAVA_APPLET_NOT_LOADED = 0x20002;

EndUserStrings.WEB_LIBRARY_INSTALLED_VERSION_NOT_SUPPORTED = 0x20003;
EndUserStrings.WEB_LIBRARY_INSTALL_PACKAGE = 0x20004;
EndUserStrings.WEB_LIBRARY_UPDATE_PACKAGE = 0x20005;
EndUserStrings.WEB_LIBRARY_USER_MANUAL = 0x20006;
EndUserStrings.WEB_LIBRARY_WEB_EXTENSION_INSTALL_PACKAGE = 0x20007;
EndUserStrings.WEB_LIBRARY_CHECK_JRE_VERSION = 0x20008;

//--------------------------------------------------------------------------------

EndUserStrings.DESCRIPTIONS = [];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_OS_NOT_SUPPORTED] = 
[
	'Інсталяційний пакет web-бібліотеки підпису для вашої системи відсутній', 
	'Инсталяционный  пакет web-библиотеки подписи для вашей системы отсутствует',
	'The installation package of the library for the web signature for your system is absent'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_NOT_INSTALLED_OR_RUN] = 
[
	'Бібліотеку web-підпису не запущено або не інстальовано у системі. Для продовження необхідно запустити або інсталювати бібліотеку web-підпису.', 
	'Библиотеку web-подписи не запущено или не установлено в системе. Для продолжения необходимо запустить или установить библиотеку web-подписи.', 
	'The library for the web signature isn\'t started or not installed in system. To continue, it is necessary to start or install library for the web signature.'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_INSTALLED_VERSION_NOT_SUPPORTED] = 
[
	'Інстальована версія web-бібліотеки підпису більше не підтримується. Для продовження необхідно інсталювати оновлену версію web-бібліотеки підпису.', 
	'Инсталированная версия web-библиотеки подписи больше не поддерживается. Для продолжения необходимо установить обновленную версию web-библиотеки подписи.', 
	'The installed version of library for the web signature isn\'t supported any more. To continue, it is necessary to install the updated version of the library for the web signature.'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_JAVA_APPLET_NOT_LOADED] = 
[
	'Java-апплет не завантажено. Необхідно перевірити версію JRE', 
	'Java-апплет не загружен. Необходимо проверить версию JRE', 
	'Java applet isn\'t loaded. It is necessary to check the version of JRE'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_INSTALL_PACKAGE] = 
[
	'Інсталяційний пакет web-бібліотеки підпису',
	'Инсталяционный пакет web-библиотеки подписи', 
	'Install package of library for the web signature'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_UPDATE_PACKAGE] = 
[
	'Оновлення web-бібліотеки підпису', 
	'Обновление web-библиотеки подписи', 
	'Update of library for the web signature'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_USER_MANUAL] = 
[
	'Настанова користувача', 
	'Руководство пользователя', 
	'User manual'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_WEB_EXTENSION_INSTALL_PACKAGE] = 
[
	'Інсталяційний пакет бібліотеки підпису (web-розширення)',
	'Инсталяционный пакет библиотеки подписи (web-расширение)', 
	'Install package of library for the web signature (web-extension)'
];
EndUserStrings.DESCRIPTIONS[
	EndUserStrings.WEB_LIBRARY_CHECK_JRE_VERSION] = 
[
	'Перевірити версію JRE', 
	'Проверить версию JRE', 
	'Check JRE version'
];

//--------------------------------------------------------------------------------

EndUserStrings.getString = function(string, language) {
	var descrArr;
	var descr;

	descrArr = EndUserStrings.DESCRIPTIONS[string];
	if (typeof descrArr == 'undefined') {
		descrArr = "";
	}

	descr = descrArr[language - 1];
	if (typeof descr == 'undefined')
		return descrArr[0];

	return descr;
}

//================================================================================

//================================================================================


//================================================================================

// euswll.js

//================================================================================
//=============================================================================

var EndUserLibraryLoader = function(libraryType, id, language, 
	noJavaApplet, webExtensionFirst) {
	this.m_id = id;
	this.m_library = null;
	this.m_language = language;
	this.m_supportedLibraryTypes = [];
	this.m_supportedLibraryTypesIndex = 0;
	this.m_noJavaApplet = noJavaApplet | false;
	this.m_webExtensionFirst = webExtensionFirst | false;
	this.m_errorResult = null;

	switch (libraryType) {
		case EndUserLibraryLoader.LIBRARY_TYPE_JAVA_APPLET:
		case EndUserLibraryLoader.LIBRARY_TYPE_SIGN_AGENT:
		case EndUserLibraryLoader.LIBRARY_TYPE_WEB_EXTENSION:
		case EndUserLibraryLoader.LIBRARY_TYPE_NPAPI:
		case EndUserLibraryLoader.LIBRARY_TYPE_ACTIVE_X:
			this.m_libraryType = libraryType;
			break;

		default:
			this.m_libraryType = EndUserLibraryLoader.LIBRARY_TYPE_DEFAULT;
			break;
	}

	if (this.m_libraryType == EndUserLibraryLoader.LIBRARY_TYPE_DEFAULT) {
		if (this.m_webExtensionFirst && 
				EndUserLibraryLoader.isWebExtensionSupported()) {
			this.m_supportedLibraryTypes.push(
				EndUserLibraryLoader.LIBRARY_TYPE_WEB_EXTENSION);
		}

		if (EndUserLibraryLoader.isSignAgentSupported()) {
			this.m_supportedLibraryTypes.push(
				EndUserLibraryLoader.LIBRARY_TYPE_SIGN_AGENT);
		}

		if (!this.m_webExtensionFirst && 
				EndUserLibraryLoader.isWebExtensionSupported()) {
			this.m_supportedLibraryTypes.push(
				EndUserLibraryLoader.LIBRARY_TYPE_WEB_EXTENSION);
		}

		if (EndUserLibraryLoader.isNPAPISupported()) {
			this.m_supportedLibraryTypes.push(
				EndUserLibraryLoader.LIBRARY_TYPE_NPAPI);
		}

		if (EndUserLibraryLoader.isActiveXSupported()) {
			this.m_supportedLibraryTypes.push(
				EndUserLibraryLoader.LIBRARY_TYPE_ACTIVE_X);
		}
		
		if (!this.m_noJavaApplet && 
				EndUserLibraryLoader.isJavaAppletSupported()) {
			this.m_supportedLibraryTypes.push(
				EndUserLibraryLoader.LIBRARY_TYPE_JAVA_APPLET);
		}
	} else {
		this.m_supportedLibraryTypes.push(this.m_libraryType);
	}

	this.onload = function(library){};
	this.onerror = function(msg, errorCode, libraryOrNull){};
	this.getLibraryType = function() {return this.m_libraryType};
};

//=============================================================================

EndUserLibraryLoader.LIBRARY_TYPE_DEFAULT		= 0;
EndUserLibraryLoader.LIBRARY_TYPE_JAVA_APPLET	= 1;
EndUserLibraryLoader.LIBRARY_TYPE_SIGN_AGENT	= 2;
EndUserLibraryLoader.LIBRARY_TYPE_WEB_EXTENSION	= 3;
EndUserLibraryLoader.LIBRARY_TYPE_NPAPI			= 4;
EndUserLibraryLoader.LIBRARY_TYPE_ACTIVE_X		= 5;

EndUserLibraryLoader.EU_DEFAULT_LANG = 0;
EndUserLibraryLoader.EU_UA_LANG = 1;
EndUserLibraryLoader.EU_RU_LANG = 2;
EndUserLibraryLoader.EU_EN_LANG = 3;

//=============================================================================

EndUserLibraryLoader.isJavaAppletSupported = function() {
	var info = new EndUserBrowserInfo();

	if (this.m_noJavaApplet)
		return false;
	
	switch (info.GetName()) {
		case EndUserBrowserInfo.BROWSER_NAME_FIREFOX:
			if (info.GetVersion() > 44)
				return false;
			return true;
			
		case EndUserBrowserInfo.BROWSER_NAME_CHROME:
			if (info.GetVersion() > 44)
				return false;
			return true;
		
		case EndUserBrowserInfo.BROWSER_NAME_OPERA:
			if (info.GetVersion() > 30)
				return false;
			return true;

		case EndUserBrowserInfo.BROWSER_NAME_EDGE:
		case EndUserBrowserInfo.BROWSER_NAME_UNKNOWN:
			return false;

		default:
			return true;
	}
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.isSignAgentSupported = function() {
	var info = new EndUserBrowserInfo();

	switch (info.GetName()) {
		case EndUserBrowserInfo.BROWSER_NAME_IE:
			if (info.GetVersion() < 10)
				return false;
			break;

		case EndUserBrowserInfo.BROWSER_NAME_SAFARI:
			if (info.GetVersion() < 7)
				return false;
			break;

		case EndUserBrowserInfo.BROWSER_NAME_UNKNOWN:
			return false;

		default:
			break;
	}

	switch (info.GetOSName()) {
		case EndUserBrowserInfo.OS_NAME_WINDOWS:
			break;

		case EndUserBrowserInfo.OS_NAME_MAC:
			break;

		case EndUserBrowserInfo.OS_NAME_UNKNOWN:
			return false;

		default:
			return false;
	}

	return true;
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.isWebExtensionSupported = function() {
	var info = new EndUserBrowserInfo();

	switch (info.GetName()) {
		case EndUserBrowserInfo.BROWSER_NAME_CHROME:
			break;

		case EndUserBrowserInfo.BROWSER_NAME_OPERA:
			if (info.GetVersion() < 35)
				return false;
			break;

		case EndUserBrowserInfo.BROWSER_NAME_FIREFOX:
			if (info.GetVersion() < 50)
				return false;
			break;

		case EndUserBrowserInfo.BROWSER_NAME_UNKNOWN:
			return false;

		default:
			return false;
	}

	switch (info.GetOSName()) {
		case EndUserBrowserInfo.OS_NAME_UNKNOWN:
			return false;

		default:
			break;
	}

	return true;
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.isNPAPISupported = function() {
	var info = new EndUserBrowserInfo();

	switch (info.GetName()) {
		case EndUserBrowserInfo.BROWSER_NAME_FIREFOX:
			if (info.GetVersion() > 51)
				return false;
			break;

		default:
			return false;
	}

	return true;
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.isActiveXSupported = function() {
	var info = new EndUserBrowserInfo();

	switch (info.GetName()) {
		case EndUserBrowserInfo.BROWSER_NAME_IE:
			if (info.GetVersion() < 9 || 
				(info.GetArch() == EndUserBrowserInfo.BROWSER_ARCH_NAME_X64)) {
				return false;
			}
			break;

		default:
			return false;
	}

	return true;
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.isWebExtensionInstalled = function() {
	var info = new EndUserBrowserInfo();
	var webExtensionAddress = (info.GetName() == 
		EndUserBrowserInfo.BROWSER_NAME_FIREFOX) ? 
		EU_SIGN_WEB_EXTENSION_ADDRESS_FIREFOX : 
		EU_SIGN_WEB_EXTENSION_ADDRESS;

	if (document.getElementsByClassName(webExtensionAddress).length != 1)
		return false;

	return true;
};

//=============================================================================

EndUserLibraryLoader.prototype.getLibraryType = function() {
	return this.m_libraryType;
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype.load = function() {
	var pThis = this;

	setTimeout(function() {
		pThis._load(pThis.m_supportedLibraryTypes[
			pThis.m_supportedLibraryTypesIndex]);
		}, 1);
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype.unload = function() {
	var el = document.getElementById(this.m_id);
	if (el && el.parentNode)
		el.parentNode.removeChild(el);
};

//=============================================================================

EndUserLibraryLoader.prototype._load = function(libraryType) {
	switch (libraryType) {
		case EndUserLibraryLoader.LIBRARY_TYPE_JAVA_APPLET: {
			this._loadSignApplet();
			return;
		}

		case EndUserLibraryLoader.LIBRARY_TYPE_SIGN_AGENT: {
			this._loadSignAgent();
			return;
		}

		case EndUserLibraryLoader.LIBRARY_TYPE_WEB_EXTENSION: {
			this._loadWebExtension();
			return;
		}
		
		case EndUserLibraryLoader.LIBRARY_TYPE_NPAPI: {
			this._loadNPAPI();
			return;
		}
		
		case EndUserLibraryLoader.LIBRARY_TYPE_ACTIVE_X: {
			this._loadActiveX();
			return;
		}	
	}
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._onLoad = function() {
	this.m_libraryType = this.m_supportedLibraryTypes[
		this.m_supportedLibraryTypesIndex];
	try {
		this.onload(this.m_library);
	} catch (e) {
		console.log(e);
	}
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._onLoadError = function(
	loadNext, msg, code, library) {
	if (this.m_errorResult == null) {
		this.m_errorResult = {
			"msg": msg,
			"code": code,
			"library": library
		};
	}

	var el = document.getElementById(this.m_id);
	if (el && el.parentNode)
		el.parentNode.removeChild(el);

	if (!loadNext) {
		try {
			this.onerror(
				this.m_errorResult.msg, 
				this.m_errorResult.code,
				this.m_errorResult.library);
		} catch (e) {
			console.log(e);
		}
		return;
	}

	this.m_supportedLibraryTypesIndex++;
	if (this.m_supportedLibraryTypesIndex >= 
			this.m_supportedLibraryTypes.length) {
		try {
			this.onerror(
				this.m_errorResult.msg, 
				this.m_errorResult.code,
				this.m_errorResult.library);
		} catch (e) {
			console.log(e);
		}
		return;
	}

	this.load();
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._loadSignApplet = function() {
	var applet = document.createElement("applet");

	applet.setAttribute("codebase", EU_SIGN_APPLET_CODEBASE);
	applet.setAttribute("code", EU_SIGN_APPLET_CODE);
	applet.setAttribute("archive", EU_SIGN_APPLET_NAME);
	applet.setAttribute("cache_archive", EU_SIGN_APPLET_NAME);
	applet.setAttribute("cache_version", EU_SIGN_APPLET_VERSION);
	applet.setAttribute("separate_jvm", true);

	applet.setAttribute("id", this.m_id);
	applet.setAttribute("width", "100%");
	applet.setAttribute("height", "1");

	document.body.appendChild(applet);

	this.m_library = document.getElementById(this.m_id);

	this._waitForSignAppletLoad(this, 
		EU_SIGN_APPLET_LOAD_DELAY,
		EU_SIGN_APPLET_LOAD_WAITS);
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._waitForSignAppletLoad = function(
	pThis, delay, waits) {
	try {
		pThis.m_library.SetUIMode(false);
	} catch (e) {
		if (waits != 0) {
			setTimeout(function () {
				pThis._waitForSignAppletLoad(
					pThis, delay, waits - 1);
			}, delay, waits);
			return;
		}

		var msg = '';

		msg = EndUserError.getErrorDescriptionEx(
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED, 
			pThis.m_language) + '. ';
		msg += EndUserStrings.getString(
			EndUserStrings.WEB_LIBRARY_JAVA_APPLET_NOT_LOADED,
			pThis.m_language)

		msg += '<br><br>';

		msg += '<a style="text-indent:18px" href="' + 
			EU_SIGN_APPLET_CHECK_JRE_URL + '">' + 
			EndUserStrings.getString(
				EndUserStrings.WEB_LIBRARY_CHECK_JRE_VERSION,
				pThis.m_language) + '</a>';

		pThis._onLoadError(true, msg,
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED,
			null);

		return;
	}

	pThis._onLoad(pThis.m_library);
};

//=============================================================================

EndUserLibraryLoader.prototype._appendWebWrapperObject = function(
	address, port, isAddressClassId, isActiveX) {
	try {
		var library = new EUSignCP(address, port);

		var object = document.createElement("object");
		object.setAttribute("id", this.m_id);
		if (isAddressClassId) {
			if (isActiveX) {
				object.setAttribute("classid", address);
				object.setAttribute("height", "1px");
			} else {
				object.type = address;
				object.classid = address;
				object.height = "1px";
			}
		} else {
			object.classid = "EUSignCP.class";
			object.style = "display:none";
			object.style.visibility = "hidden";
		}
		document.body.appendChild(object);

		for (var field in library)
			object[field] = library[field];

		this.m_library = document.getElementById(this.m_id);

		return true;
	} catch (e) {
		return false;
	}
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._onLoadWebLibraryError = function(error) {
	var pThis = this;
	var library = pThis.m_library;

	var loadNext = true;
	var msg = '';

	msg += EndUserError.getErrorDescriptionEx(
		EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED, 
		pThis.m_language);

	msg += ". ";

	switch (error.GetErrorCode()) {
		case EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED:
			msg += EndUserStrings.getString(
				EndUserStrings.WEB_LIBRARY_NOT_INSTALLED_OR_RUN,
				pThis.m_language);
		break;
		
		case EndUserError.ERROR_LIBRARY_VERSION_NOT_SUPPORTED:
			msg += EndUserStrings.getString(
				EndUserStrings.WEB_LIBRARY_INSTALLED_VERSION_NOT_SUPPORTED,
				pThis.m_language);
			loadNext = false;
			pThis.m_errorResult = null;
		break;

		default:
			msg += EndUserError.getErrorDescriptionEx(
				error.GetErrorCode(), pThis.m_language) + ".";
			break;
	} 

	msg += '<br><br>';

	if (library.GetInstallURL() != null) {
		if (error.GetErrorCode() == 
				EndUserError.ERROR_LIBRARY_VERSION_NOT_SUPPORTED) {
			msg += '<div><a style="text-indent:18px" href="' + 
				library.GetUpdateURL() + '">' + 
				EndUserStrings.getString(
					EndUserStrings.WEB_LIBRARY_UPDATE_PACKAGE, 
					pThis.m_language) + '</a></div>';
		} else {
			msg += '<div><a style="text-indent:18px" href="' + 
				library.GetInstallURL() + '">' + 
				EndUserStrings.getString(
					EndUserStrings.WEB_LIBRARY_INSTALL_PACKAGE, 
					pThis.m_language) + '</a></div>';
			if (EndUserLibraryLoader.isWebExtensionSupported()) {
				msg += '<div><a style="text-indent:18px" href="' + 
					library.GetWebExtensionInstallURL() + '">' + 
					EndUserStrings.getString(
						EndUserStrings.WEB_LIBRARY_WEB_EXTENSION_INSTALL_PACKAGE, 
						pThis.m_language) + '</a></div>';
			}
		}

		msg += '<div><a style="text-indent:18px" href="' + 
			library.GetHelpURL() + '">' + 
			EndUserStrings.getString(
				EndUserStrings.WEB_LIBRARY_USER_MANUAL, 
				pThis.m_language) + '</a></div>';
	} else {
		msg += "<div>" + EndUserStrings.getString(
			EndUserStrings.WEB_LIBRARY_OS_NOT_SUPPORTED, 
			pThis.m_language) + "</div>";
	}

	pThis._onLoadError(loadNext, msg,
		error.GetErrorCode(), library);
}

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._loadWebLibrary = function() {
	var pThis = this;

	pThis.m_library.Load(
		function() {
			pThis._onLoad();
		}, 
		function(error) {
			pThis._onLoadWebLibraryError(error);
		});
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._loadSignAgent = function() {
	if (!this._appendWebWrapperObject(
			EU_SIGN_AGENT_DEFAULT_ADDRESS, 
			(window.location.protocol == 'http:') ? 
				EU_SIGN_AGENT_DEFAULT_HTTP_POPT : 
				EU_SIGN_AGENT_DEFAULT_HTTPS_POPT, 
			false, false)) {
		
		var msg = EndUserError.getErrorDescriptionEx(
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED, 
			this.m_language);
		this._onLoadError(true, msg, 
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED,
			null);
		return;
	}

	this._loadWebLibrary();
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._loadWebExtension = function() {
	if (!this._appendWebWrapperObject(
			EU_SIGN_WEB_EXTENSION_ADDRESS, "", false, false)) {
		var msg = EndUserError.getErrorDescriptionEx(
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED, 
			this.m_language);
		this._onLoadError(true, msg, 
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED,
			null);
		return;
	}

	this._loadWebLibrary();
};

//=============================================================================

EndUserLibraryLoader.prototype._loadPlugin = function(
	classId, loadDelay, loadWaits, isActiveX) {
	if (!this._appendWebWrapperObject(
			classId, "", true, isActiveX)) {
		var msg = EndUserError.getErrorDescriptionEx(
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED, 
			this.m_language);
		this._onLoadError(true, msg, 
			EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED,
			null);
		return;
	}

	this._waitForPluginLoad(this, loadDelay, loadWaits);
};

//-----------------------------------------------------------------------------

EndUserLibraryLoader.prototype._waitForPluginLoad = function(
	pThis, delay, waits) {
	pThis.m_library.Load(
		function() {
			pThis._onLoad();
		},
		function(error) {
			if ((error.GetErrorCode() == 
					EndUserError.ERROR_LIBRARY_COMUNICATION_FAILED) && 
				(waits != 0)) {
				setTimeout(function () {
					pThis._waitForPluginLoad(
						pThis, delay, waits - 1);
				}, delay, waits);
				return;
			}

			pThis._onLoadWebLibraryError(error);
		});
};

//=============================================================================


EndUserLibraryLoader.prototype._loadNPAPI = function() {
	this._loadPlugin(
		EU_SIGN_WEB_NPAPI_ADDRESS, 
		EU_SIGN_WEB_NPAPI_LOAD_DELAY,
		EU_SIGN_WEB_NPAPI_LOAD_WAITS,
		false);
};

//=============================================================================

EndUserLibraryLoader.prototype._loadActiveX = function() {
	this._loadPlugin(
		EU_SIGN_WEB_ACTIVE_X_ADDRESS, 
		EU_SIGN_WEB_ACTIVE_X_LOAD_DELAY,
		EU_SIGN_WEB_ACTIVE_X_LOAD_WAITS,
		true);
};

//=============================================================================

//================================================================================

