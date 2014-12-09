'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.factory:PatientsFactory
 * @description
 * # PatientsFactory
 * Factory of Patients informations
 */
app.factory('Patients', ['$resource', function($resource){

	//var Patients = $resource('http://demo9892644.mockable.io/patients');
	var Patients = $resource('/patients/all');
	//var Patient = $resource('http://demo9892644.mockable.io/patients/:id', {id:'@id'});
	var Patient = $resource('/patients/:id', {id:'@id'});
	//var File = $resource('http://demo9892644.mockable.io/patients/:id/file', {id:'@id'});
	var File = $resource('/patients/:id/file', {id:'@id'});
	var MedicalRecord = $resource('/files/:id/prescriptions', {id: '@id'});

	var Factory = {

		// Return all patients
		getPatients : function(){
			return Patients.query({}).$promise;
		},

		// Return 1 patient
		getPatient : function(id){
			return Patient.get({id:id}).$promise;
		},

		getMedicalRecord : function(patientId) {
			return File.get({id:patientId}).$promise ;
		},

		getPrescriptions : function (medicalRecordId) {
			return MedicalRecord.query({id:medicalRecordId}).$promise;
		}
	};

	return Factory;
}]);

app.factory('Prescription', ['$resource', function ($resource) {
	var MedicinesSearch = $resource('/medicines/search/:keyword', {keyword:'@keyword'});

	var Factory = {
		searchMedication: function (keyword) {
			return MedicinesSearch.query({keyword: keyword}).$promise;
		}
	};

	return Factory;
}]);
