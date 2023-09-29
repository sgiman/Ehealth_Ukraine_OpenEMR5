/****************************************
 *  specialist.js
 *  Specialist
 *
 *  OpenEMR      
 *  http://www.open-emr.org
 *
 *  API EHEALTH version 1.0                          
 *  Writing by sgiman, 2020
 *****************************************/
'use strict';
function SpecialistViewModel() {
    var self = this;
	var server = "/openemr/interface/ehealth";

    self.educations = ko.observableArray([new SpecialistEducationViewModel()]).extend({ required: true });
    self.qualifications = ko.observableArray(); // [new DoctorQualificationViewModel()]
    self.specialities = ko.observableArray([new SpecialistSpecialityViewModel()]).extend({ required: true });
    self.science_degree = ko.observableArray();

   	//-----------------------------------
    //              Countries (Dictionaries)
	//-----------------------------------
	self.countries = ko.observableArray([]);
    self.getCountries = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/Countries.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.countries.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    if (data.values[key].code === "UA")
                        options.unshift({ value: data.values[key].code, text: data.values[key].name });
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.countries.push.apply(self.countries, options);
        });
    };
    self.getCountries();

   	//-----------------------------------
    //        EducationDegrees (Dictionaries)
	//-----------------------------------
    self.educationDegrees = ko.observableArray([]);
    self.getEducationDegrees = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/EducationDegrees.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.educationDegrees.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.educationDegrees.push.apply(self.educationDegrees, options);
        });
    };
    self.getEducationDegrees();

   	//-----------------------------------
    //       QualificationTypes (Dictionaries)
	//-----------------------------------
    self.qualificationTypes = ko.observableArray([]);
    self.getQualificationTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/QualificationTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.qualificationTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            self.qualificationTypes.push.apply(self.qualificationTypes, options);
        });
    };
    self.getQualificationTypes();

   	//-----------------------------------
    //          SpecialityTypes (Dictionaries)
	//-----------------------------------
    self.specialityTypes = ko.observableArray([]);
    self.getSpecialityTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/SpecialityTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.specialityTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.specialityTypes.push.apply(self.specialityTypes, options);
        });
    };
    self.getSpecialityTypes();

   	//-----------------------------------
    //         SpecialityLevels (Dictionaries)
	//-----------------------------------
    self.specialityLevels = ko.observableArray([]);
    self.getSpecialityLevels = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/SpecialityLevels.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.specialityLevels.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.specialityLevels.push.apply(self.specialityLevels, options);
        });
    };
    self.getSpecialityLevels();

   	//-----------------------------------
    //  SpecificQualificationTypes (Dictionaries)
	//-----------------------------------
    self.specificQualificationTypes = ko.observableArray([]);
    self.getSpecificQualificationTypes = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/SpecificQualificationTypes.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.specificQualificationTypes.removeAll();
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.specificQualificationTypes.push.apply(self.specificQualificationTypes, options);
        });
    };
    self.getSpecificQualificationTypes();

   	//-----------------------------------
    //  	    ScienceDegrees (Dictionaries)
	//-----------------------------------
    self.scienceDegrees = ko.observableArray([]);
    self.getScienceDegrees = function () {
        var request = $.ajax({
            url: server + "/api/Dictionaries/ScienceDegrees.php",
            type: "GET",
            datatype: "json"
        });
        request.done(function (data) {
            self.scienceDegrees.removeAll();
            //console.log(data.values);
            var options = [];
            for (var key in data.values) {
                if (data.values.hasOwnProperty(key)) {
                    options.push({ value: data.values[key].code, text: data.values[key].name });
                }
            }
            //console.log(options);
            self.scienceDegrees.push.apply(self.scienceDegrees, options);
        });
    };
    self.getScienceDegrees();

    // ------ addEducation ------
	self.addEducation = function () {
        self.educations.push(new  SpecialistEducationViewModel());
    };

    // ------ removeEducation ------
    self.removeEducation = function (education) {
        self.educations.remove(education);
    };

    // ------ addQualification ------
    self.addQualification = function () {
        self.qualifications.push(new  SpecialistQualificationViewModel());
    };

    // ------ removeQualification ------
    self.removeQualification = function (qualification) {
        self.qualifications.remove(qualification);
    };

    // ------ addSpeciality ------
    self.addSpeciality = function () {
        self.specialities.push(new  SpecialistSpecialityViewModel());
    };

    // ------ removeSpeciality ------
    self.removeSpeciality = function (speciality) {
        self.specialities.remove(speciality);
    };

    // ------ addScienceDegree ------
    self.addScienceDegree = function () {
        self.science_degree.push(new  SpecialistScienceDegreeViewModel());
    };

    // ------ removeScienceDegree ------
    self.removeScienceDegree = function (science_degree) {
        self.science_degree.remove(science_degree);
    };

   	//---------------------
    //  	   getNeededValues
	//---------------------
    self.getNeededValues = function () {
        var temp = {};
        temp.educations = [];
        self.educations().forEach(function (object) {
            var t = object.getNeededValues();
            if (!jQuery.isEmptyObject(t))
                temp.educations.push(t);
        });
        if (jQuery.isEmptyObject(temp.educations))
            temp.educations = (function () { return; })();

        temp.qualifications = [];
        self.qualifications().forEach(function (object) {
            var t = object.getNeededValues();
            if (!jQuery.isEmptyObject(t))
                temp.qualifications.push(t);
        });
        if (jQuery.isEmptyObject(temp.qualifications))
            temp.qualifications = (function () { return; })();

        temp.specialities = [];
        self.specialities().forEach(function (object) {
            var t = object.getNeededValues();
            if (!jQuery.isEmptyObject(t))
                temp.specialities.push(t);
        });
        if (jQuery.isEmptyObject(temp.specialities))
            temp.specialities = (function () { return; })();

        if (self.science_degree().length > 0) {
            temp.science_degree = self.science_degree()[0].getNeededValues();
        }

        return temp;
    };
    self.specialistErrors = ko.validation.group(this);
}
