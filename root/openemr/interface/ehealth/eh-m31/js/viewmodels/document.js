/**********************************************************
 *  document.js
 *  Документи, що засвідчують особу керівника 
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
**********************************************************/
function IsExpirationDateRequired(docType) {
	var mandatoryTypes = [
		"NATIONAL_ID",
		"COMPLEMENTARY_PROTECTION_CERTIFICATE",
		"PERMANENT_RESIDENCE_PERMIT",
		"REFUGEE_CERTIFICATE",
		"TEMPORARY_CERTIFICATE",
		"TEMPORARY_PASSPORT"
	];
	return mandatoryTypes.indexOf(docType) > -1;
}

function GetRegExpDependOnDocType(docType) {  /// better to check this before using
	switch (docType) {
		case 'PASSPORT': return '^((?![ЫЪЭЁ])([А-ЯҐЇІЄ])){2}[0-9]{6}$';
		case 'NATIONAL_ID': return '^[0-9]{9}$';
		case 'BIRTH_CERTIFICATE': return '^(?![ыъэ@%& $ ^#\`~:,.*|}{?!])[А-ЯҐЇІЄа-яґїіє0-9 №\\\"()-]+$';
		case 'COMPLEMENTARY_PROTECTION_CERTIFICATE': return '^((?![ЫЪЭЁ])([А-ЯҐЇІЄ])){2}[0-9]{6}$';
		case 'PERMANENT_RESIDENCE_PERMIT': return '^((?![ЫЪЭЁ])([А-ЯҐЇІЄ])){2}[0-9]{6}$';
		case 'REFUGEE_CERTIFICATE': return '^((?![ЫЪЭЁ])([А-ЯҐЇІЄ])){2}[0-9]{6}$';
		case 'TEMPORARY_CERTIFICATE': return '^((?![ЫЪЭЁ])([А-ЯҐЇІЄ])){2}[0-9]{6}$';
		case 'TEMPORARY_PASSPORT': return '^(?![ыъэ@%&$^#\`~:,.*|}{?!])[А-ЯҐЇІЄа-яґїіє0-9№\\\"()-]+$';
		default: return '.*';
	}
}

function DocumentViewModel(birth_date, requireExpDate) {
	var self = this;

	self.type = ko.observable().extend({ required: true });
	self.number = ko.observable().trimmed().extend({
		required: true,
		maxLength: {
			params: 25,
			message: function (params) {
				return "Довжина цього поля не може перевищувати " + params + " символів.";
			}
		},
		validation: {
			validator: function (number) {
				return true;  // validation turned off

				//var re = new RegExp(GetRegExpDependOnDocType(self.type()));
				//return re.test('' + number + '');
			},
			message: 'Перевірте коректність введених даних.'
		}
	});

	self.isExpirationDateRequired_ko = ko.pureComputed(function () {
		if (requireExpDate)
			return IsExpirationDateRequired(self.type());
		return false;
	});

	self.issued_by = ko.observable().extend({
		required: {
			onlyIf: function () { return self.isExpirationDateRequired_ko(); }
		} });

	self.issued_at = ko.observable().extend({
		required: {
			onlyIf: function () { return self.isExpirationDateRequired_ko(); }
		},
		validation: {
			validator: function (cur_issued_at) {
				if (birth_date && birth_date() && cur_issued_at) {
					var today = new Date();
					var f1 = cur_issued_at <= today.toISOString();
					var f2 = cur_issued_at >= birth_date();
					return f1 && f2;
				} else
					return true;
			},
			message: 'Дата видачі документу має бути у межах з дня народження власника документа до сьогоднішнього дня.'
		}
	});

	self.expiration_date = ko.observable().extend({
		required: {
			onlyIf: function () {
				return self.isExpirationDateRequired_ko();
			}
		},
		validation: {
			validator: function (cur_expiration_date) {
				if (self.isExpirationDateRequired_ko() == false)
					return true;
				var today = new Date();
				return cur_expiration_date >= today.toISOString();
			},
			message: 'Документ є просроченим.'
		}
	});
	

	self.initValues = function (initData) {
		if (initData) {
			setTimeout(function () { self.type(initData.type); }, 2000);
			// self.type(initData.type);
			self.number(initData.number);
			self.issued_by(initData.issued_by);
			self.issued_at(initData.issued_at);
			self.expiration_date(initData.expiration_date);
		}
	};
	
	self.getNeededValues = function () {
		var temp = {};
		temp.type = returnIfDataExist(self.type());
		temp.number = returnIfDataExist(self.number());
		temp.issued_by = returnIfDataExist(self.issued_by());
		temp.issued_at = returnIfDataExist(self.issued_at());
		if (self.isExpirationDateRequired_ko())
			temp.expiration_date = returnIfDataExist(self.expiration_date());
		return temp;
	};
}
