'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.controller:medicalRecordCtrl
 * @description
 * # medicalRecordCtrl
 * Controller of the lifeMonitorDoctorApp
 */
app.controller('medicalRecordCtrl', ['$rootScope', '$scope', '$stateParams', 'Patients', 'filterFilter', 'typeFilter', function ($rootScope, $scope, $stateParams, Patients, filterFilter, typeFilter) {
	
	$scope.medicalRecordContents = [];
	$scope.numberOfAllergy = 0;
	$scope.numberOfIllness = 0;
	$scope.numberOfPrescription = 0;
	$scope.numberInProgress = 0;
	$scope.numberSelfMedication = 0;

	// Filters
	$scope.filters = {
		illnessFilter: true,
		allergyFilter: true,
		prescriptionFilter: true,
		selfMedicationFilter: true,
		inProgressFilter: false,
		search:''
	};

	// medicalRecord filtered
	$scope.filtered;

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
			                type: treatment.prescription ? 'prescription' : 'treatment',
			                name: treatment.description,
			                date: treatment.prescription && treatment.prescription.date ? new Date(treatment.prescription.date) :  new Date(treatment.date),
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
						if(!treatment.prescription){
							$scope.numberSelfMedication++;
						}
					});
					$scope.numberInProgress = $scope.medicalRecordContents.filter(filterInProgress).length;

				}, function (error) {
					console.error(error);
				});

				$rootScope.loading[1] = false;
				$scope.filtered = $scope.medicalRecordContents;
			}
		);
	};
	$scope.loadMedicalRecord();

	// ----- Function for Dates

	// Add Days to a date
	function addDays(date, days) {
		var result = new Date(date);
		result.setDate(date.getDate() + days);
		return result;
	}

	// Watch filters and filters items
	$scope.$watchCollection('filters',  function(value) {
		if($scope.filtered != undefined){
			// Create filtered
			$scope.filtered = filterFilter(typeFilter($scope.medicalRecordContents, $scope.filters.allergyFilter, $scope.filters.illnessFilter, $scope.filters.prescriptionFilter, $scope.filters.inProgressFilter, $scope.filters.selfMedicationFilter), value.search);

			// Then calculate noOfPages
			$scope.updatePagination();
		}
	});

	// Update pagination
	$scope.updatePagination = function() {
		$scope.currentPage = 1;
		$scope.pageCount();
	};

	// In progress filter
	function filterInProgress(element) {
		return element.treatmentInProgress == true;
	}

	// ----- Sort informations
	$scope.predicate = '-date';
	$scope.reverse = false ;


	// Pagination
	$scope.itemsPerPage = 10;
  	$scope.currentPage = 1;
	$scope.noOfPages;

  	$scope.prevPage = function() {
    	if ($scope.currentPage > 1) {
      		$scope.currentPage--;
    	}
  	};

  	$scope.prevPageDisabled = function() {
    	return $scope.currentPage === 1 ? 'disabled' : '';
  	};

  	$scope.pageCount = function() {
		if($scope.filtered != undefined) {
			$scope.noOfPages = Math.ceil($scope.filtered.length / $scope.itemsPerPage);
		} else {
			$scope.noOfPages = Math.ceil($scope.medicalRecordContents.length / $scope.itemsPerPage);
		}
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
	    if ( start + rangeSize -1 <= $scope.noOfPages ) {
	    	for (var i=start; i<=start+rangeSize-1; i++) {
		      ret.push(i);
		    }
	    } else {
		    var rangmin = start - (rangeSize - ($scope.noOfPages - start +1)) ;
	    	if( rangmin < 1){
	    		rangmin = 1 ;
	    	}
	    	for (i=rangmin; i<= $scope.noOfPages; i++) {
	      		ret.push(i);
	    	}
		}
	    return ret;
	};

	$scope.setPage = function(n) {
    	$scope.currentPage = n;
  	};
}]);
