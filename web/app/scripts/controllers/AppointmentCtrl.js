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
app.controller('AppointmentCtrl', ['$rootScope', '$scope', '$stateParams', '$compile', 'Patients', 'uiCalendarConfig', 'Appointments', function ($rootScope, $scope, $stateParams, $compile, Patients, uiCalendarConfig, Appointments) {

    // Appointment to add
    $scope.appointment = {
        // patientId: $stateParams.id,
        doctor : {"id":1,"name":"Dr Jekyll"},  // Doctor who add an appointment (by default)
        date: '' , // the date
        confirmed: true // confirmed by default
    };

    // Requests for appointments
    $scope.appointmentsRequests = [];

    // ErrorInfo
    $scope.error = {};


    // -- Calendar

    /* event source that pulls from google.com */
    $scope.eventSource = {
        //url: "/appointments/search/Dr%20Jekyll",
        events : [],
        className: 'gcal-event',           // an option!
        currentTimezone: 'Europe/Paris' // an option!
    };

    /* event source that contains custom events on the scope */
    $scope.events = [
        /*{
            title: 'Title',
            start: new Date(),
            // end    : '2015-02-11',
            allDay : false,
            editable : true
        }*/
    ];

    /* event source that calls a function on every view switch
    $scope.eventsF = function (start, end, timezone, callback) {
        var s = new Date(start).getTime() / 1000;
        var e = new Date(end).getTime() / 1000;
        var m = new Date(start).getMonth();
        var events = [{title: 'Feed Me ' + m,start: s + (50000),end: s + (100000),allDay: false, className: ['customFeed']}];
        callback(events);
    };
    */

    /* event sources array*/
    $scope.eventSources = [$scope.events, $scope.eventSource];

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
        //alert(date.title + ' was clicked ');
    };

    /*
    // alert on Drop
    $scope.alertOnDrop = function(event, delta, revertFunc, jsEvent, ui, view){
        alert('Event Droped to make dayDelta ' + delta);
    };
    */

    // Function which add a appointment
    $scope.addEvent = function () {
        Patients.getMedicalRecord($stateParams.id).then(
            // OK
            function(medicalRecord){
                Appointments.addAppointments(medicalRecord.id, $scope.appointment)
                    .success(function (data, status, headers, config) {
                        $scope.myCalendar.fullCalendar('refetchEvents');
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
    $scope.updateAppointments = function(){

        Appointments.getAppointments("Dr Jekyll").then(
            // getAppointments OK
            function(appointments){
                var appointmentsEvents = [];
                var appointmentsRequests = [];

                appointments.forEach(function (appointment) {
                    if(appointment.confirmed){
                        // Add event in calendar
                        // Get patient name for event title
                        Patients.getPatient(appointment.patientId).then(
                            // getPatient OK
                            function(patient){
                                appointmentsEvents.push({
                                    title: 'Appointment with ' + patient.name,
                                    start: appointment.date
                                });
                            },
                            // getPatient ERROR
                            function(msg){
                                $scope.error.title = 'Could not get appointments';
                                $scope.error.message = msg;
                                $('#errorModal').modal('show');
                            }
                        );
                    } else {
                        // appointment not confirmed, Add appointment to Request list
                        appointmentsRequests.push(appointment);
                    }
                });
                $scope.appointmentsRequests = appointmentsRequests;
                $scope.eventSource.events = appointmentsEvents;
                $scope.myCalendar.fullCalendar('refetchEvents');

            },
            // getAppointments ERROR
            function(msg){
                $scope.error.title = 'Could not get appointments';
                $scope.error.message = msg;
                $('#errorModal').modal('show');
            }
        )
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
        // TODO
    }

    // Cancel an appointment
    $scope.cancelAppointment = function(appointment){
        // TODO
    }


}]);