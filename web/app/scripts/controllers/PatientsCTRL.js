'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.controller:patientsCtrl
 * @description
 * # patientsCtrl
 * Controller of the lifeMonitorDoctorApp
 */
app.controller('patientsCtrl', ['$scope', 'Patients', function ($scope, Patients) {

	// Request result
	$scope.patients = [];
  
	$scope.loadPatients = function() {
		Patients.getPatients().then(
			// OK
	    	function(patients){
	    		$scope.patients = patients ;
	    	}, 
	    	// ERROR
	    	function(msg){
	    		alert('Error in loadPatients method');
	    	}
	    );
	};

	$scope.loadPatients();
}]);
