app.controller('TreatmentVisualizationCtrl', ['$scope', '$stateParams', 'Treatments', '$q', function ($scope, $stateParams, Treatments, $q) {
	$scope.days;
	$scope.treatment;
	$scope.loadTreatment = function () {
		if ($scope.days && $scope.treatment) {
			return;
		}
		var promises = [
			Treatments.getTreatment($stateParams.treatmentId),
			Treatments.getIntakes($stateParams.treatmentId)
		];
		$q.all(promises).then(function (results) {
			$scope.treatment = results[0];
			var now = new Date();
			var intakes = results[1];
			$scope.days = {};
			intakes.filter(function (intake) {
				return (new Date(intake.time)).getTime() < now.getTime();
			}).forEach(function (intake) {
				var time = new Date(intake.time);
				intake.time = new Date(time);
				if (!intake.id && intake.time.getTime() < now.getTime()) {
					intake.missed = true;
				}
				var day = (new Date(intake.time.getFullYear(), intake.time.getMonth(), intake.time.getDate())).toISOString();
				if (!($scope.days[day] instanceof Array)) {
					$scope.days[day] = [];
				}
				$scope.days[day].push(intake);
			});
		}, function (error) {
			alert(error.message);
		});
	};
	$scope.loadTreatment();
}]);