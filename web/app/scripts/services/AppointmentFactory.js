/**
 * Created by edmondvanovertveldt on 11/02/15.
 */

app.factory('Appointments', ['$resource', '$http', function ($resource, $http) {
    var Appointments = $resource('/appointments/search/:name', {name:'@name'});

    var Factory = {
        getAppointments: function(name){
            return Appointments.query({'name': name}).$promise;
        },

        addAppointments: function(medicalRecordId, appointment ){
            return $http({
                'url': '/files/' + medicalRecordId + '/appointments',
                'method': 'POST',
                'data': appointment
            });
        }
    };

    return Factory;
}]);
