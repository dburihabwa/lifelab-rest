'use strict';

/**
 * @ngdoc overview
 * @name lifeMonitorDoctorApp
 * @description
 * # lifeMonitorDoctorApp
 *
 * Main module of the application.
 */

 // Definie le module lifeMonitorDoctorApp et ses dépendances
 var app = angular.module('lifeMonitorDoctorApp', ['ngAnimate','ngResource','ui.router']);
  app.config(function ($stateProvider, $urlRouterProvider, $httpProvider) {
    $httpProvider.defaults.useXDomain = true;
    $httpProvider.defaults.headers.common = 'Content-Type: application/json';
    delete $httpProvider.defaults.headers.common['X-Requested-With'];


    //Set default route
    $urlRouterProvider.otherwise('/patients');

    //Declare states

    // state Home : search patient
    $stateProvider
      .state('home', {
        url: '/patients',
        views: {
          'searchPatient': { templateUrl: 'views/home-patientSearch.html', controller: 'patientsCtrl' },
          'patientInformations': { templateUrl: 'views/noPatient.html'}
        }
      })

      // state patient's informations
      .state('patientInformations', {
        url: '/patients/:id',
        abstract: true,
        views: {
          'searchPatient': { templateUrl: 'views/home-patientSearch.html', controller: 'patientsCtrl' },
          'patientInformations': { templateUrl: 'views/home-patient.html', controller: 'patientCtrl' },
        }
      })

        // sub state medical record of patient's informations
        .state('patientInformations.medicalRecord', {
          url: '/medicalRecord',
          templateUrl: 'views/home-patient-medicalRecord.html',
          controller: 'medicalRecordCtrl'
        });

        // sub state add prescription of patient's informations ...
  });
