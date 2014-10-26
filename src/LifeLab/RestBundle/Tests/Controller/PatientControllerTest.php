<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Patient;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase {
    /**
     * Test if GET /patients/all returns an array
     */
    public function testGetAll() {
        $client = static::createClient();
        $client->request('GET', '/patients/all');
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $patients = json_decode($client->getResponse()->getContent());
        $this->assertEquals(gettype($patients), 'array');
    }
    
    /**
     * Test if POST /patients/ creates a new patient
     */
    public function testPost() {
        $client = static::createClient();
        $name = 'test post patient';
        $client->request('POST', '/patients', array('name' => $name));
        //Test if the patient is returned
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $patient = json_decode($client->getResponse()->getContent());
        $this->assertObjectHasAttribute('id', $patient);
        $this->assertEquals($patient->name, $name);
    }
    
    /**
     * Test if PUT /patients actually modifies a patient
     */
    public function testPut() {
        $client = static::createClient();
        $name = 'test post patient';
        $client->request('POST', '/patients', array('name' => $name));
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $patient = json_decode($client->getResponse()->getContent());
        $this->assertObjectHasAttribute('id', $patient);
        $this->assertEquals($patient->name, $name);
        $newName = 'test post patient 2';
        $id = $patient->id;
        $client->request('PUT', '/patients/' . $id, array('name' => $newName));
        $patient = json_decode($client->getResponse()->getContent());
        $this->assertEquals($patient->id, $id);
        $this->assertEquals($patient->name, $newName);
    }
}
