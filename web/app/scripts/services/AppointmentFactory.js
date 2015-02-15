/**
 * Created by edmondvanovertveldt on 11/02/15.
 */

app.factory('Appointments', ['$resource', '$http', function ($resource, $http) {
    var Appointments = $resource('/appointments/search/:name', {name:'@name'});
    var Appointment = $resource('/appointments/:id', null,
        {
            'update': { method:'PUT' }
        });

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
        },

        update: function(appointment){
            return Appointment.update({'id': appointment.id}, appointment).$promise;
        },

        delete: function(appointment){
            return Appointment.delete({'id' : appointment.id}).$promise;
        }
    };

    return Factory;
}]);
