'use strict';

app.controller('PrescriptionCtrl', ['$rootScope', '$scope', '$stateParams', '$state', 'Patients', 'Medicines', 'Doctors', 'MedicalRecords', '$location', function ($rootScope, $scope, $stateParams, $state, Patients, Medicines, Doctors, MedicalRecords, $location) {
	// For nav redirections
	$scope.$state = $state;
	$scope.$stateParams = $stateParams;

	$scope.prescription = {};
	$scope.treatment = {};
	$scope.medicines = [];
	$scope.doctors = [];
	$scope.error = {};

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
		$scope.prescription.date = (new Date()).toISOString().replace(/\.\d{3}/, '');
		Patients.getPatient($stateParams.id).then(function (patient) {
			if (!$scope.treatment) {
				$scope.error.title = 'Treatment was not initialized properly';
				$scope.error.message = 'Please make sure you filled in all the fields in the form.'
				$('#errorModal').modal('show');
				return;
			}
			if (!$scope.treatment.medicine) {
				$scope.error.title = 'Missing medicaction';
				$scope.error.message = 'The treatment must indicate some sort of medication.';
				$('#errorModal').modal('show');
				return;
			}
			if (!$scope.treatment.date) {
				var error = new Error('Treatment date is missing!');
				$scope.error.title = 'Missing treatment date';
				$scope.error.message = 'The treatment must indicate a start date.';
				$('#errorModal').modal('show');
				return;
			}
			var medicalFile = patient.medical_file;
			$scope.prescription.medicalFile = medicalFile;
			$scope.treatment.date = $scope.treatment.date.toISOString().replace(/\.\d{3}/, '');
			MedicalRecords.addPrescription(medicalFile.id, $scope.prescription).success(function (data, status, headers, config) {
				$scope.treatment.prescription = data;
				$scope.treatment.medicalFile = data.medicalFile;
				MedicalRecords.addTreatment(medicalFile.id, $scope.treatment).success(function (dataTreatTreatment, statusTreatment, headersTreatment, configTreatment) {
					$location.path('/patients/' + $stateParams.id + '/medicalRecord');
				}).error(function (dataTreatment, statusTreatment, headersTreatment, configTreatment) {
					$scope.error.title = 'Could not save treatment';
					$scope.error.message = dataTreatment;
					$('#errorModal').modal('show');
				});
			}).error(function (data, status, headers, config) {
				$scope.error.title = 'Could not save prescription';
				$scope.error.message = data;
				$('#errorModal').modal('show');
			});
		}, function (error) {
			$scope.error.title = 'Could not retrieve medical record';
			$scope.error.message = error.message;
			$('#errorModal').modal('show');
		});
		$('#prescriptionDoctorsModal').modal('hide');
	};


	$scope.listDoctors();
}]);