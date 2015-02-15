<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Doctor;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use JMS\Serializer\SerializerBuilder;


class DoctorControllerTest extends WebTestCase {
	private $medicalFile;
	private $medicine;
	private $entityManager;

	public function __construct() {
		$this->kernel = new \AppKernel('test', true);
		$this->kernel->boot();
		$this->container = $this->kernel->getContainer();
		$this->entityManager = $this->container->get('doctrine')->getManager();
	}

	public function testPostDoctor() {
		$client = static::createClient();
		$doctor = new Doctor();
		$doctor->setName('testPostDoctor');
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($doctor, 'json');
		$client->request('POST', 
			'/doctors',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(200, $client->getResponse()->getstatusCode());
		$doctor = $serializer->deserialize($client->getResponse()->getContent(),
			'LifeLab\RestBundle\Entity\Doctor',
			'json');
		$this->assertTrue($doctor->getId() != NULL);		
		$doctor = $this->entityManager->merge($doctor);
		$this->entityManager->remove($doctor);
	}

	public function testPostDoctorWithoutName() {
		$client = static::createClient();
		$doctor = new Doctor();
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($doctor, 'json');
		$client->request('POST', 
			'/doctors',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getstatusCode());
	}

	public function testGetDoctor() {
		$client = static::createClient();
		$doctor = new Doctor();
		$doctor->setName('testPostDoctor');
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($doctor, 'json');
		$client->request('POST', 
			'/doctors',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(200, $client->getResponse()->getstatusCode());
		$doctor = $serializer->deserialize($client->getResponse()->getContent(),
			'LifeLab\RestBundle\Entity\Doctor',
			'json');
		$this->assertTrue($doctor->getId() != NULL);		
		$doctor = $this->entityManager->merge($doctor);
		$client->request('GET', 
			'/doctors/' . $doctor->getId(),
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			NULL);
		$doctorRetrieved  = $serializer->deserialize($client->getResponse()->getContent(),
			'LifeLab\RestBundle\Entity\Doctor',
			'json');
		$this->assertEquals($doctor->getId(), $doctorRetrieved->getId());
		$this->assertEquals($doctor->getName(), $doctorRetrieved->getName());
		$this->entityManager->remove($doctor);
	}

	public function testGetDoctorWithWrongId() {
		$client = static::createClient();
		$client->request('GET', 
			'/doctors/-1',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			NULL);
		$this->assertEquals(404, $client->getResponse()->getstatusCode());
	}

	public function testDeleteDoctor() {
		$client = static::createClient();
		$doctor = new Doctor();
		$doctor->setName('testPostDoctor');
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($doctor, 'json');
		$client->request('POST', 
			'/doctors',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(200, $client->getResponse()->getstatusCode());
		$doctor = $serializer->deserialize($client->getResponse()->getContent(),
			'LifeLab\RestBundle\Entity\Doctor',
			'json');
		$this->assertTrue($doctor->getId() != NULL);		
		$doctor = $this->entityManager->merge($doctor);
		$client->request('DELETE', 
			'/doctors/' . $doctor->getId(),
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			NULL);
		$this->assertEquals(200, $client->getResponse()->getstatusCode());
		$client->request('GET', 
			'/doctors/' . $doctor->getId(),
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			NULL);		
		$this->assertEquals(404, $client->getResponse()->getstatusCode());
	}

	public function testDeleteDoctorWithWrongId() {
		$client = static::createClient();
		$client->request('DELETE', 
			'/doctors/-1',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			NULL);
		$this->assertEquals(404, $client->getResponse()->getstatusCode());
	}
}