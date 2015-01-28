'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.factory:PatientsFactory
 * @description
 * # PatientsFactory
 * Factory of Patients informations
 */
app.factory('Patients', ['$resource', function($resource){
	var Patients = $resource('/patients/all');
	var Patient = $resource('/patients/:id', {id:'@id'});
	var File = $resource('/patients/:id/file', {id:'@id'});
	var MedicalRecord = $resource('/files/:id/treatments', {id: '@id'});

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

		getTreatments : function (medicalRecordId) {
			return MedicalRecord.query({id:medicalRecordId}).$promise;
		}
	};

	return Factory;
}]);

app.factory('Doctors', ['$resource', function ($resource) {
	var Doctors = $resource('/doctors/all');
	var Doctor = $resource('/doctors/:id', {id: '@id'});

	var Factory = {
		getAll: function () {
			return Doctors.query().$promise;
		},
		get: function (id) {
			return Doctor.get({'id': id}).$promise;
		}
	};

	return Factory;
}]);

app.factory('Medicines', ['$resource', function ($resource) {
	var Search = $resource('/medicines/search/:keyword', {'keyword': 'keyword'});
	var Factory = {
		search: function (keyword) {
			return Search.query({'keyword': keyword}).$promise;
		}
	};
	return Factory;
}]);

app.factory('MedicalRecords', ['$resource', '$http', function ($resource, $http) {
	var medicalRecord = $resource('/files/:id', {'id': '@id'});
	var Factory = {
		addPrescription: function (medicalRecordId, prescription) {
			return $http({
				'url': '/files/' + medicalRecordId + '/prescriptions',
				'method': 'POST',
				'data': prescription
			});
		},
		addTreatment: function (medicalRecordId, treatment) {
			return $http({
					'url': '/files/' + medicalRecordId + '/treatments',
					'method': 'POST',
					'data': treatment
			});
		},
		get: function (id) {
			return medicalRecord.get(id).$promise;
		}
	};
	return Factory;
}]);