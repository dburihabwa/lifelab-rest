'use strict';

// Unit test of controller patientsCtrl
describe('Controller: patientsCtrl', function () {

  var scope, mockedPatientsFactory, patientsCtrl;

  // load the controller's module
  // Mock PatientsFactory
  beforeEach(module('lifeMonitorDoctorApp', function($provide) {
    mockedPatientsFactory = {
      getPatients: jasmine.createSpy()
    };

    $provide.value('Patients', mockedPatientsFactory);
  }));

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    patientsCtrl = $controller('patientsCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    scope.loadPatients();
    expect(mockedPatientsFactory.getPatients).toHaveBeenCalled();
  });
});
