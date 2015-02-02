'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.controller:medicalRecordCtrl
 * @description
 * # medicalRecordCtrl
 * Controller of the lifeMonitorDoctorApp
 */
app.controller('medicalRecordCtrl', ['$rootScope', '$scope', '$stateParams', 'Patients', function ($rootScope, $scope, $stateParams, Patients) {
	
	$scope.medicalRecordContents = [];
	$scope.numberOfAllergy;
	$scope.numberOfIllness;
	$scope.numberOfPrescription;
	$scope.numberInProgress;

	// Filters
	$scope.illnessFilter;
	$scope.allergyFilter;
	$scope.prescriptionFilter;
	$scope.inProgressFilter;

	// Build medical record
	$scope.loadMedicalRecord = function(){
		var medicalRecord = null;

		// Request to rest service
		Patients.getMedicalRecord($stateParams.id).then(
			// OK
		   	function(content){
		   		medicalRecord = content ;
		  	},
		 	// ERROR
			function(msg){
		   		alert('Error in getMedicalRecord(' + $stateParams.id + ') method');
		   	}
		)
		.then(
			function(){
				// Build medical record

				$scope.numberOfAllergy = medicalRecord.allergies.length;
				$scope.numberOfIllness = medicalRecord.illnesses.length;

				medicalRecord.allergies.forEach(function (allergy) {
					$scope.medicalRecordContents.push({
		                type: 'allergy',
		                name: allergy.name
		            });
				});
				medicalRecord.illnesses.forEach(function (illness) {
					$scope.medicalRecordContents.push({
		                type: 'illness',
		                name: illness.name,
		                date: illness.date
		            });
				});

				Patients.getTreatments(medicalRecord.id).then(function (treatments) {
					$scope.numberOfPrescription = treatments.length;

					treatments.forEach(function (treatment) {
						$scope.medicalRecordContents.push({
			                type: 'prescription',
			                name: treatment.description,
			                date: treatment.prescription && treatment.prescription.date ? new Date(treatment.prescription.date) :  null,
			                doctor: treatment.prescription && treatment.prescription.doctor.name ? treatment.prescription.doctor.name : null,
							treatment: {
								date: new Date(treatment.date),
								frequency: treatment.frequency,
								quantity: treatment.quantity,
								medicine: {
									name: treatment.medicine.name,
									shape: treatment.medicine.shape
								}
							},
							duration: treatment.duration,
							treatmentInProgress: addDays(new Date(treatment.date), treatment.duration) >= new Date()
			            });
					});
					$scope.numberInProgress = $scope.medicalRecordContents.filter(filterInProgress).length;

				}, function (error) {
					console.error(error);
				});

				$rootScope.loading[1] = false; 
			}
		);
	};
	$scope.loadMedicalRecord();

	// Function for Dates

	// Add Days to a date
	function addDays(date, days) {
		var result = new Date(date);
		result.setDate(date.getDate() + days);
		return result;
	}

	//Filter
	function filterInProgress(element) {
		return element.treatmentInProgress == true;
	}

	// Sort informations
	$scope.predicate = '-date';
	$scope.reverse = false ;


	// Pagination
	$scope.itemsPerPage = 10;
  	$scope.currentPage = 1;

  	$scope.prevPage = function() {
    	if ($scope.currentPage > 1) {
      		$scope.currentPage--;
    	}
  	};

  	$scope.prevPageDisabled = function() {
    	return $scope.currentPage === 1 ? 'disabled' : '';
  	};

  	$scope.pageCount = function() {
		var nbcontents = 0;

		if ($scope.illnessFilter == true && $scope.numberOfIllness != undefined) {
			nbcontents = nbcontents + $scope.numberOfIllness;
		}
		if ($scope.allergyFilter == true && $scope.numberOfAllergy != undefined) {
			nbcontents = nbcontents + $scope.numberOfAllergy ;
		}
		if ($scope.prescriptionFilter == true && $scope.numberOfPrescription != undefined) {
			nbcontents = nbcontents + $scope.numberOfPrescription ;
		}

    	return Math.ceil(nbcontents / $scope.itemsPerPage);
  	};

  	$scope.nextPage = function() {
    	if ($scope.currentPage < $scope.pageCount()) {
      		$scope.currentPage++;
    	}
  	};

 	$scope.nextPageDisabled = function() {
    	return $scope.currentPage === $scope.pageCount() ? 'disabled' : '';
  	};

  	$scope.range = function() {
	    var rangeSize = 5;
	    var ret = [];
	    var start;

	    start = $scope.currentPage;
	    if ( start + rangeSize -1 <= $scope.pageCount() ) {
	    	for (var i=start; i<=start+rangeSize-1; i++) {
		      ret.push(i);
		    }
	    } else {
		    var rangmin = start - (rangeSize - ($scope.pageCount() - start +1)) ;
	    	if( rangmin < 1){
	    		rangmin = 1 ;
	    	}
	    	for (i=rangmin; i<= $scope.pageCount(); i++) {
	      		ret.push(i);
	    	}
		}
	    return ret;
	};

	$scope.setPage = function(n) {
    	$scope.currentPage = n;
  	};
}]);
