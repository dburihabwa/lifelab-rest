'use strict';

app.controller('PrescriptionCtrl', ['$rootScope', '$scope', '$stateParams', '$state', 'Patients', 'Medicines', 'Doctors', 'MedicalRecords', '$location', function ($rootScope, $scope, $stateParams, $state, Patients, Medicines, Doctors, MedicalRecords, $location) {
	// For nav redirections
	$scope.$state = $state;
	$scope.$stateParams = $stateParams;

	$scope.prescription = {};
	$scope.treatment = {};
	$scope.medicines = [];
	$scope.doctors = [];

	$scope.listDoctors = function () {
		Doctors.getAll().then(function (doctors) {
			$scope.doctors = doctors;
		}, function (error) {
			alert('A problem occcured when loading the list  doctors :\n' + error.message);
		});
	};

	$scope.searchMedication = function (keyword) {
		if (keyword) {
			Medicines.search(keyword).then(function (medicines) {
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
			MedicalRecords.addPrescription(medicalFile.id, $scope.prescription).success(function (data, status, headers, config) {
				$scope.treatment.prescription = data;
				$scope.treatment.medicalFile = data.medicalFile;
				MedicalRecords.addTreatment(medicalFile.id, $scope.treatment).success(function (dataTreatTreatment, statusTreatment, headersTreatment, configTreatment) {
					$location.path('/patients/' + $stateParams.id + '/medicalRecord');
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