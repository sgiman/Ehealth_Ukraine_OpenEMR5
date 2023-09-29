/**************************************************
 *  koValidationWithExtraFeatures.js
 *  Validation with Extra Features (knockout)
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 **************************************************/

if (window.ko) {
	ko.subscribable.fn.trimmed = function () {
		return ko.computed({
			read: function () {
				return this() ? this().trim() : this();
			},
			write: function (value) {
				this(value ? value.trim() : value);
				this.valueHasMutated();
			},
			owner: this
		}).extend({
			notify: 'always'
		});
	};

/*	
    // ----- Validation checkReg ------ 		
	ko.validation.rules['checkReg'] = {
		validator: function (value, params) {
			if (!value || value === '')
				return true;
			var re = new RegExp(params);
			return re.test('' + value + '');
		},
		message: function (params) {
			return 'Перевірте коректність введених даних.';
		},
		params: 1
	};
	
    // ----- Validation check_tax_id ------ 		
	ko.validation.rules['check_tax_id'] = {
		validator: function (value, params) {
			var re = new RegExp(params);
			if (value === '' || model.employee_request().party().no_tax_id()) {
				//re = new RegExp(/[Є-ЯҐ]{2}[0-9]{6}/);
				//return re.test('' + value + '');
				return true;
			} else {
				return re.test('' + value + '');
			}
		},
		message: function (params) {
			return 'Перевірте коректність введених даних.';
		},
		params: 1
	};

    // ----- ko.validation.init ------ 		
	ko.validation.init({
		registerExtenders: true,
		messagesOnModified: true,
		insertMessages: true,
		parseInputAttributes: true,
		decorateInputElement: true,
		messageTemplate: null,
		grouping: {
			deep: true,
			live: true,
			observable: true
		}
	}, true);
	
	ko.validation.rules.required.message = "Поле має бути заповненим/вибраним.";
	ko.validation.rules.email.message = "Некоректний формат електронної пошти.";
	ko.validation.rules.date.message = "Некоректний формат дати.";
	ko.validation.rules.dateISO.message = "Некоректний формат дати.";


} else {
	console.log('Validation and some features for knockout were not set cause of problems with knockout.');
*/

}

