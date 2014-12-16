'use strict';

app.controller('PrescriptionCtrl', ['$rootScope', '$scope', '$stateParams', '$state', 'Prescription', '$http', function ($rootScope, $scope, $stateParams, $state, Prescription, $http){
	// For nav redirections
	$scope.$state = $state;
	$scope.$stateParams = $stateParams;

	$scope.prescription = {};
	$scope.treatment = {};
	$scope.medicines = [];

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
		$scope.prescription.medicalFile = {'id': parseInt($stateParams.id, 10)};
		$scope.prescription.doctor = {'id': 1};
		$scope.prescription.date = new Date();
				
		$http({
			'url': '/files/' + $stateParams.id + '/prescriptions',
			'method': 'POST',
			'data': $scope.prescription
		}).success(function (data, status, headers, config) {
			$scope.treatment.prescription = data;
			$scope.treatment.medicalFile = data.medicalFile;
			$http({
				'url': '/files/' + $stateParams.id + '/treatments',
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
	};
}]);
