'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.controller:patientCtrl
 * @description
 * # patientCtrl
 * Controller of the lifeMonitorDoctorApp
 */
app.controller('patientCtrl', ['$rootScope', '$scope', '$stateParams', '$state', 'Patients', function ($rootScope, $scope, $stateParams, $state, Patients){
	// For nav redirections
	$scope.$state = $state;
	$scope.$stateParams = $stateParams;

	$rootScope.loading = [true,true];

	$scope.patient = null;

	$scope.loadPatient = function() {
		Patients.getPatient($stateParams.id).then(
			// OK
		   	function(patient){
		   		$scope.patient = patient ;
		   		$rootScope.loading[0] = false; 
		   	},
		   	// ERROR
		   	function(msg){
		   		alert('Error in loadPatient(' + $stateParams.id + ') method');
		   	}
		);
	};
	$scope.loadPatient();
}]);
