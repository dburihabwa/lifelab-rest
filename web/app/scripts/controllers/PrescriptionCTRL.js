'use strict';

app.controller('PrescriptionCtrl', ['$rootScope', '$scope', '$stateParams', '$state', 'Patients', 'Prescription', 'Doctor', '$http', function ($rootScope, $scope, $stateParams, $state, Patients ,Prescription, Doctor, $http) {
	// For nav redirections
	$scope.$state = $state;
	$scope.$stateParams = $stateParams;

	$scope.prescription = {};
	$scope.treatment = {};
	$scope.medicines = [];
	$scope.doctors = [];

	$scope.listDoctors = function () {
		Doctor.getAll().then(function (doctors) {
			$scope.doctors = doctors;
			console.log(JSON.stringify(doctors, null, '\t'));
		}, function (error) {
			alert('A problem occcured when loading the list  doctors :\n' + error.message);
		});
	};

	$scope.searchMedication = function (keyword) {
		if (keyword) {
			Prescription.searchMedication(keyword).then(function (medicines) {
				$scope.medicines = medicines;
			}, function (error) {
				console.error(error);
			});
		}
	};

	$scope.submit = function () {
		$scope.prescription.date = new Date();

		Patients.getPatient($stateParams.id).then(function (patient) {
			var medicalFile = patient.medical_file;
			$scope.prescription.medicalFile = medicalFile;
			$http({
				'url': '/files/' + medicalFile.id + '/prescriptions',
				'method': 'POST',
				'data': $scope.prescription
			}).success(function (data, status, headers, config) {
				$scope.treatment.prescription = data;
				$scope.treatment.medicalFile = data.medicalFile;
				$http({
					'url': '/files/' + medicalFile.id + '/treatments',
					'method': 'POST',
					'data': $scope.treatment
				}).success(function (dataTreatTreatment, statusTreatment, headersTreatment, configTreatment) {
					alert('Prescrition and treatment saved!');
				}).error(function (dataTreatment, statusTreatment, headersTreatment, configTreatment) {
					alert('Oops! Couldn\'t save the treatment');
				});
			}).error(function (data, status, headers, config) {
				alert('Oops! Couldn\'t save the prescription');
			});
		}, function (error) {
			alert(error);
		});

		$('#prescriptionDoctorsModal').modal('hide');
	};


	$scope.listDoctors();
}]);
