'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.filter:offset
 * @description
 * # offset
 * filter for pagination
 */

app.filter('offset', function() {
  return function(input, start) {
    start = parseInt(start, 10);
    return input.slice(start);
  };
});