/**
 * Created by edmondvanovertveldt on 10/02/15.
 */

'use strict';

/**
 * @ngdoc function
 * @name lifeMonitorDoctorApp.controller:medicalRecordCtrl
 * @description
 * # medicalRecordCtrl
 * Controller of the lifeMonitorDoctorApp
 */
app.controller('AppointmentCtrl', ['$rootScope', '$scope', '$stateParams', '$compile', '$q', 'Patients', 'uiCalendarConfig', 'Appointments', function ($rootScope, $scope, $stateParams, $compile, $q, Patients, uiCalendarConfig, Appointments) {

    // Appointment to add
    $scope.appointment = {
        patient_id: $stateParams.id, // patient id
        doctor : {"id":1,"name":"Dr Jekyll"},  // Doctor who add an appointment (by default)
        date: '' , // the date
        confirmed: true // confirmed by default
    };

    // Requests for appointments
    $scope.appointmentsRequests = [];

    // ErrorInfo
    $scope.error = {};
    $scope.messageAlert;


    // -- Calendar

    /* event source that pulls from google.com */
    $scope.eventSource = {
        //url: "/appointments/search/Dr%20Jekyll",
        events : [],
        className: 'gcal-event',           // an option!
        currentTimezone: 'Europe/Paris' // an option!
    };

    /* event sources array*/
    $scope.eventSources = [$scope.eventSource];

    /* On dayClick */
    $scope.OnDayClick = function( date, jsEvent, view){
        if(view.name == 'agendaDay'){
            // Add appointment : Fix me !
            $scope.appointment.date = date.toISOString().replace(/\.\d{3}/, '') + "+01:00";
            $scope.addEvent();
        } else {
            // Change view
            $scope.myCalendar.fullCalendar( 'changeView', 'agendaDay');
            $scope.myCalendar.fullCalendar('gotoDate', date);
        }
    };

    /* alert on eventClick */
    $scope.alertOnEventClick = function( date, jsEvent, view){
        alert($scope.formatDate(date) + ' : ' + date.title);
    };

    // Function which add a appointment
    $scope.addEvent = function () {
        Patients.getMedicalRecord($stateParams.id).then(
            // OK
            function(medicalRecord){
                Appointments.addAppointments(medicalRecord.id, $scope.appointment)
                    .success(function (data, status, headers, config) {
                        $scope.updateAppointments();
                        $scope.messageAlert = 'Appointment has benn added'
                        setTimeout(function ()
                        {
                            $scope.$apply(function()
                            {
                                $scope.messageAlert = undefined;
                            });
                        }, 4000);
                    })
                    .error(function (data, status, headers, config) {
                        $scope.error.title = 'Could not save appointment';
                        $scope.error.message = data;
                        $('#errorModal').modal('show');
                    });
            },
            // ERROR
            function(msg){
                alert('Can not connect to MedicalRecord(' + $stateParams.id + ')');
            }
        )
    };

    // Function which update doctor's appointments
    $scope.updateAppointments = function() {
        var deferred = $q.defer();

        Appointments.getAppointments("Dr Jekyll").then(
            // getAppointments OK
            function (appointments) {
                var appointmentsRequests = [];
                var events = [];

                appointments.forEach(function (appointment, index) {
                    // Get patient name for event title
                    Patients.getPatient(appointment.patient_id).then(
                        // getPatient OK
                        function (patient) {
                            if (appointment.confirmed) {
                                // Add event in calendar
                                events.push({
                                    title: 'Appointment with ' + patient.name,
                                    start: appointment.date
                                });
                            }
                            else {
                                // appointment not confirmed, Add appointment to Request list
                                appointmentsRequests.push({
                                    appointment: appointment,
                                    patientName: patient.name
                                });
                            }
                            if(index == appointments.length -1){
                                deferred.resolve({
                                    appointmentsRequests : appointmentsRequests,
                                    events : events
                                });
                            }
                        },
                        // getPatient ERROR
                        function (msg) {
                            $scope.error.title = 'Could not get appointments';
                            $scope.error.message = msg;
                            $('#errorModal').modal('show');
                            deferred.reject();
                        }
                    );
                });
            },
            // getAppointments ERROR
            function(msg){
                $scope.error.title = 'Could not get appointments';
                $scope.error.message = msg;
                $('#errorModal').modal('show');
                deferred.reject();
            }
        );

        $q.all(deferred.promise).then(
            function(appointments){
                // update data
                $scope.myCalendar.fullCalendar( 'removeEvents');
                $scope.appointmentsRequests = appointments.appointmentsRequests;
                $scope.eventSource.events = appointments.events;
            }
        );
    };
    $scope.updateAppointments();

    /* Render Tooltip */
    $scope.eventRender = function( event, element, view ) {
        element.attr({'tooltip': event.title,
            'tooltip-append-to-body': true});
        $compile(element)($scope);
    };

    /* config object */
    $scope.uiConfig = {
        calendar:{
            height: 450,
            editable: true,
            header:{
                left: 'month agendaDay',
                center: 'title',
                right: 'today prev,next'
            },
            defaultTimedEventDuration: '00:30:00',
            eventLimit: true,
            selectable: true,
            selectHelper: true,
            timezone: 'Europe/Paris',
            /* select: function(start, end) {
                var title = prompt('Event Title:');
                var eventData;
                if (title) {
                    eventData = {
                        title: title,
                        start: start,
                        end: end
                    };
                    $('#calendar').fullCalendar('renderEvent', eventData, true);
                }
                $('#calendar').fullCalendar('unselect');
            },
            */
            dayClick: $scope.OnDayClick,
            eventClick: $scope.alertOnEventClick,
            // eventDrop: $scope.alertOnDrop,
            eventRender: $scope.eventRender
        }
    };

    // Display date format
    $scope.formatDate = function(dateString) {
        return new moment(dateString).format("dddd, MMMM Do YYYY, h:mm:ss a");
    }

    // Accept an appointment
    $scope.acceptAppointment = function(appointment){
        // confirm the appointment
        appointment.confirmed = true;

        // update appointment with confirmed value
        Appointments.update(appointment).then(
            // getAppointments OK
            function(appointments){
                // update data
                $scope.updateAppointments();
                $scope.messageAlert = 'Appointment has been confirmed'
                setTimeout(function ()
                {
                    $scope.$apply(function()
                    {
                        $scope.messageAlert = undefined;
                    });
                }, 4000);
            },
            // getAppointments ERROR
            function(msg){
                $scope.error.title = 'Could not update appointment';
                $scope.error.message = msg;
                $('#errorModal').modal('show');
            }
        )
    };

    // Cancel an appointment
    $scope.cancelAppointment = function(appointment){
        Appointments.delete(appointment).then(
            // getAppointments OK
            function(appointments){
                // update data
                $scope.updateAppointments();

            },
            // getAppointments ERROR
            function(msg){
                $scope.error.title = 'Could not delete appointment';
                $scope.error.message = msg;
                $('#errorModal').modal('show');
            }
        )
    }
}]);
