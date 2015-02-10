'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.filter:offset
 * @description
 * # offset
 * filter for pagination
 */

/**
 * filter for pagination
 */
app.filter('startFrom', function() {
  return function(input, start) {
    if(input != undefined){
      start = parseInt(start, 10);
      return input.slice(start);
    }
    else {
      return [];
    }
  };
});

/**
 * Filter for item type
 */
app.filter('type', function() {

  function allergyFilter(element) {
    return element.type == "allergy";
  }

  function illnessFilter(element) {
    return element.type == "illness";
  }

  function prescriptionFilter(element) {
    return element.type == "prescription";
  }

  function inProgressFilter(element) {
    return element.treatmentInProgress == true;
  }

  function selfmedicationFilter(element) {
    return element.type == "treatment";
  }

  return function(input, allergy, illness, prescription, inProgress, selfmedication) {
    var res = new Array;
    if(input != undefined){
      if(allergy) { res = res.concat(input.filter(allergyFilter)); }
      if(illness) { res = res.concat(input.filter(illnessFilter)); }
      if(prescription) { res = res.concat(input.filter(prescriptionFilter));}
      if(selfmedication) {res = res.concat(input.filter(selfmedicationFilter));}
      if(inProgress) { res = res.filter(inProgressFilter);}
    }

    return res;
  };
});
