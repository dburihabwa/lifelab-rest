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

  function filterAllergy(element) {
    return element.type == "allergy";
  }

  function filterIllness(element) {
    return element.type == "illness";
  }

  function filterPrescription(element) {
    return element.type == "prescription";
  }

  function filterInProgress(element) {
    return element.treatmentInProgress == true;
  }

  return function(input, allergy, illness, prescription, inProgress) {
    var res = new Array;
    if(input != undefined){
      if(allergy) { res = res.concat(input.filter(filterAllergy)); }
      if(illness) { res = res.concat(input.filter(filterIllness)); }
      if(prescription) { res = res.concat(input.filter(filterPrescription));}
      if(inProgress) { res = res.filter(filterInProgress);}
    }

    return res;
  };
});
