<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Intake;
use LifeLab\RestBundle\Entity\MedicalFile;
use LifeLab\RestBundle\Entity\Medicine;
use LifeLab\RestBundle\Entity\Treatment;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use JMS\Serializer\SerializerBuilder;


class TreatmentControllerTest extends WebTestCase {
	
	private $entityManager;
	private $intake;
	private $medicalFile;
	private $medicine;
	private $treatment;

	public function __construct() {
		$this->kernel = new \AppKernel('test', true);
		$this->kernel->boot();
		$this->container = $this->kernel->getContainer();
		$this->entityManager = $this->container->get('doctrine')->getManager();
	}

	public function setUp() {	
		$this->medicalFile = new MedicalFile();
		$this->medicalFile = $this->insertMedicalFile($this->medicalFile);
		$this->medicine = new Medicine();		
		$this->medicine->setName('test');
		$this->medicine->setShape('CREAM');
		$this->medicine->setHowToTake('oral');
		$this->medicine->setDangerLevel(1);
		$this->medicine = $this->insertMedicine($this->medicine);
	}

	public function tearDown() {
		if ($this->intake != NULL && $this->intake->getId() != NULL) {
			$this->intake = $this->entityManager->merge($this->intake);
			$this->entityManager->remove($this->intake);
			$this->entityManager->flush();
		}
		if ($this->treatment != NULL && $this->treatment->getId() != NULL) {
			$this->treatment = $this->entityManager->merge($this->treatment);
			$this->entityManager->remove($this->treatment);
			$this->entityManager->flush();
		}
		if ($this->medicalFile != NULL && $this->medicalFile->getId() != NULL) {
			$this->medicalFile = $this->entityManager->merge($this->medicalFile);
			$this->entityManager->remove($this->medicalFile);
			$this->entityManager->flush();
		}
		if ($this->medicine != NULL && $this->medicine->getId() != NULL) {
			$this->medicine = $this->entityManager->merge($this->medicine);
			$this->entityManager->remove($this->medicine);
			$this->entityManager->flush();
		}
	}
	
	public function testaddIntakeToInexistingTreatment() {
		$url = "/treatments/0/intakes";
		$intake = new Intake();
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($intake, 'json');;
		$client = static::createClient();
		$client->request('POST', 
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(404, $client->getResponse()->getStatusCode());
	}

	protected function insertMedicalFile($medicalFile) {
		$this->entityManager->persist($medicalFile);
		$this->entityManager->flush();
		return $medicalFile;
	}

	protected function insertMedicine($medicine) {
		$this->entityManager->persist($medicine);
		$this->entityManager->flush();
		return $medicine;
	}

	protected function insertTreatment($treatment) {
		$this->entityManager->persist($treatment);
		$this->entityManager->flush();
		return $treatment;
	}

	protected function getTreatment($medicalFile, $date, $duration, $medicine, $frequency, $quantity) {
		$treatment = new Treatment();
		$treatment->setMedicalFile($medicalFile);
		$treatment->setDate($date);
		$treatment->setDuration($duration);
		$treatment->setMedicine($medicine);
		$treatment->setFrequency($frequency);
		$treatment->setQuantity($quantity);
		return $treatment;
	}

	public function testPostIntake() {
		$this->treatment = $this->getTreatment($this->medicalFile, new \Datetime(), 1, $this->medicine, 1, 1);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($this->treatment, 'json');
		$url = '/files/' . $this->medicalFile->getId() . '/treatments';
		$client = static::createClient();
		$client->request('POST', 
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$jsonResponse = $client->getResponse()->getContent();
		$repsonseTreatment = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Treatment', 'json');
		$this->treatment = $repsonseTreatment;

		$this->intake = new Intake();
		$this->intake->setTime($this->treatment->getDate());
		$jsonContent = $serializer->serialize($this->intake, 'json');
		$url = '/treatments/' . $this->treatment->getId() . '/intakes';
		$client->request('POST', 
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$jsonResponse = $client->getResponse()->getContent();
		$this->intake = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Intake', 'json');
		$this->assertTrue($this->intake->getId() != NULL);
	}

	public function testPostIntakeWithMissingDate() {
		$this->treatment = $this->getTreatment($this->medicalFile, new \Datetime(), 1, $this->medicine, 1, 1);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($this->treatment, 'json');
		$url = '/files/' . $this->medicalFile->getId() . '/treatments';
		$client = static::createClient();
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$jsonResponse = $client->getResponse()->getContent();
		$repsonseTreatment = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Treatment', 'json');
		$this->treatment = $repsonseTreatment;

		$this->intake = new Intake();
		$jsonContent = $serializer->serialize($this->intake, 'json');
		$url = '/treatments/' . $this->treatment->getId() . '/intakes';
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals($client->getResponse()->getStatusCode(), 400);
	}

	public function testPostIntakeWithoutIntake() {
		$this->treatment = $this->getTreatment($this->medicalFile, new \Datetime(), 1, $this->medicine, 1, 1);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($this->treatment, 'json');
		$url = '/files/' . $this->medicalFile->getId() . '/treatments';
		$client = static::createClient();
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$jsonResponse = $client->getResponse()->getContent();
		$repsonseTreatment = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Treatment', 'json');
		$this->treatment = $repsonseTreatment;
		$jsonContent = $serializer->serialize($this->intake, 'json');
		$url = '/treatments/' . $this->treatment->getId() . '/intakes';
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			null);
		$this->assertEquals($client->getResponse()->getStatusCode(), 400);
	}

	public function testPostIntakeDuplicate() {
		$this->treatment = $this->getTreatment($this->medicalFile, new \Datetime(), 1, $this->medicine, 1, 1);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($this->treatment, 'json');
		$url = '/files/' . $this->medicalFile->getId() . '/treatments';
		$client = static::createClient();
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$jsonResponse = $client->getResponse()->getContent();
		$repsonseTreatment = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Treatment', 'json');
		$this->treatment = $repsonseTreatment;

		$this->intake = new Intake();
		$this->intake->setTime(new \Datetime());
		$jsonContent = $serializer->serialize($this->intake, 'json');
		$url = '/treatments/' . $this->treatment->getId() . '/intakes';
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$jsonResponse = $client->getResponse()->getContent();
		$this->intake = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Intake', 'json');
		$this->assertTrue($this->intake->getId() != NULL);

		$intake = new Intake();
		$intake->setTime(new \Datetime());
		$jsonContent = $serializer->serialize($intake, 'json');
		$url = '/treatments/' . $this->treatment->getId() . '/intakes';
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(409, $client->getResponse()->getStatusCode());
	}

	public function testPostUnexpectedIntake() {
		$this->treatment = $this->getTreatment($this->medicalFile, new \Datetime(), 1, $this->medicine, 24, 1);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($this->treatment, 'json');
		$url = '/files/' . $this->medicalFile->getId() . '/treatments';
		$client = static::createClient();
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$jsonResponse = $client->getResponse()->getContent();
		$repsonseTreatment = $serializer->deserialize($jsonResponse, 'LifeLab\RestBundle\Entity\Treatment', 'json');
		$this->treatment = $repsonseTreatment;

		$intake = new Intake();
		$time = new \Datetime();
		$time->add(new \DateInterval('PT100S'));
		$intake->setTime($time);
		$jsonContent = $serializer->serialize($this->intake, 'json');
		$url = '/treatments/' . $this->treatment->getId() . '/intakes';
		$client->request('POST',
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}
}