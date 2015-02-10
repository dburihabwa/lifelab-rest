app.controller('TreatmentVisualizationCtrl', ['$scope', '$stateParams', 'Treatments', function ($scope, $stateParams, Treatments) {
	$scope.days = {};
	$scope.loadTreatment = function () {
		if (Object.keys($scope.days).length > 0) {
			return;
		}
		Treatments.getIntakes($stateParams.treatmentId).then(function (intakes) {
			var now = new Date();
			intakes.filter(function (intake) {
				return (new Date(intake.time)).getTime() < now.getTime();
			}).forEach(function (intake) {
				var time = new Date(intake.time);
				time = time.getTime() + (time.getTimezoneOffset() * 60 * 1000 * -1);
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
			alert(error);
			console.log(JSON.stringify(error));
		});
	};
	$scope.loadTreatment();
}]);