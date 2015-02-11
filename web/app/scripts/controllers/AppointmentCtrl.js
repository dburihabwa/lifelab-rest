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

    $scope.appointment = {};
    $scope.error = {};

    // -- Calendar

    /* event source that pulls from google.com */
    $scope.eventSource = {
        url: "/appointments/search/Dr%20Jekyll",
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

    /* event source that calls a function on every view switch */
    $scope.eventsF = function (start, end, timezone, callback) {
        var s = new Date(start).getTime() / 1000;
        var e = new Date(end).getTime() / 1000;
        var m = new Date(start).getMonth();
        var events = [{title: 'Feed Me ' + m,start: s + (50000),end: s + (100000),allDay: false, className: ['customFeed']}];
        callback(events);
    };

    /* alert on dayClick */
    $scope.OnDayClick = function( date, jsEvent, view){
        if(view.name == 'agendaDay'){
            // Add appointment : Fix me !
            $scope.appointment.date = date.toISOString().replace(/\.\d{3}/, '') + "+01:00";
            console.log("date = " + $scope.appointment.date);
            $scope.addEvent($scope.myCalendar);

        } else {
            // Change view
            $scope.myCalendar.fullCalendar( 'changeView', 'agendaDay');
            $scope.myCalendar.fullCalendar('gotoDate', date);
        }


    };
    /* alert on eventClick */
    $scope.alertOnEventClick = function( date, jsEvent, view){
        alert(date.title + ' was clicked ');
    };
    /* alert on Drop */
    /*
    $scope.alertOnDrop = function(event, delta, revertFunc, jsEvent, ui, view){
        alert('Event Droped to make dayDelta ' + delta);
    };
    */

    // Function which add a appointment
    $scope.addEvent = function () {
        Patients.getMedicalRecord($stateParams.id).then(
            // OK
            function(medicalRecord){
                $scope.appointment.doctor = {"id":1,"name":"Dr Jekyll"};

                Appointments.addAppointments(medicalRecord.id, $scope.appointment)
                    .success(function (data, status, headers, config) {
                        console.log('OK ' + $scope.appointment.doctor.name + ' ' + $scope.appointment.date + ' ' + data);
                        $scope.myCalendar.fullCalendar('refetchEvents');
                    })
                    .error(function (data, status, headers, config) {
                        console.log('error : ' + data);
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

    /* Render Tooltip */
    $scope.eventRender = function( event, element, view ) {
        element.attr({'tooltip': event.title,
            'tooltip-append-to-body': true});
        $compile(element)($scope);
    };

    /* event sources array*/
    $scope.eventSources = [$scope.events, $scope.eventSource, $scope.eventsF];

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
            eventDrop: $scope.alertOnDrop,
            eventRender: $scope.eventRender
        }
    };
}]);