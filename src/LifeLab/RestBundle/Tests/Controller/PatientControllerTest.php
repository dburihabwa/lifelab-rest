<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Patient;
use LifeLab\RestBundle\Entity\MedicalFile;

use JMS\Serializer\SerializerBuilder;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase {
    /**
     * Test if GET /patients/all returns an array
     */
    public function testGetAll() {
        $client = static::createClient();
        $client->request('GET', '/patients/all');
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $patients = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(gettype($patients), 'array');
        $serializer = SerializerBuilder::create()->build();
        foreach ($patients as $p) {
            $patient = $serializer->deserialize(json_encode($p), 'LifeLab\RestBundle\Entity\Patient','json');
            $this->assertNull($patient->getMedicalFile());
        }
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
        $this->deletePatient($patient->id);
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
        $this->deletePatient($id);
    }

    public function testGetFile() {
        $client = static::createClient();
        $patient = new Patient();
        $patient->setName("TestgetFile");
        $patient = $this->insertPatient($patient);
        $client->request('GET', '/patients/' . $patient->getId() . '/file');
        $json = $client->getResponse()->getContent();
        $serializer = SerializerBuilder::create()->build();
        $file = NULL;
        //Test that file is a parsable medical file
        try {
            $file = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\MedicalFile', 'json');
        } catch (Exception $e) {
            $this->fail('No exception should be thrown at this point');
        } finally {
            $this->deletePatient($patient->getId());
        }
        $this->assertObjectHasAttribute('id', $file);
    }

    /**
     * Inserts a user in the database using the api POST /patients/
     * @param p - Patient entity to insert
     */
    private function insertPatient($p) {
        $serializer = SerializerBuilder::create()->build();
        $client = static::createClient();
        $jsonRequest = $serializer->serialize($p, 'json');;
        $client->request('POST', '/patients', json_decode($jsonRequest, true));
        $jsonResponse = $client->getResponse()->getContent();
        $patient = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Patient', 'json');
        return $patient;
    }

    /**
     * Deletes a patient through the API DELETE /patients/:id
     * @param id - Id of the patient
     */
    private function deletePatient($id) {
        $client = static::createClient();
        $client->request('DELETE', '/patients/' . $id);
        $jsonResponse = $client->getResponse()->getContent();
        return $client->getResponse()->getStatusCode() == 200;
    }
}
