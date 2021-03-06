<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Treatment;
use LifeLab\RestBundle\Entity\MedicalFile;
use LifeLab\RestBundle\Entity\Medicine;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use JMS\Serializer\SerializerBuilder;


class MedicalFileControllerTest extends WebTestCase {

	private $medicalFile;
	private $medicine;
	private $entityManager;

	public function __construct() {

		$this->kernel = new \AppKernel('test', true);
		$this->kernel->boot();
		$this->container = $this->kernel->getContainer();
		$this->entityManager = $this->container->get('doctrine')->getManager();
	}

	public function setUp() {
		$this->medicalFile = new MedicalFile();
		$this->entityManager->persist($this->medicalFile);
		$this->medicine = new Medicine();
		$this->medicine->setName('test');
		$this->medicine->setShape('CREAM');
		$this->medicine->setHowToTake('oral');
		$this->medicine->setDangerLevel(1);
		$this->entityManager->persist($this->medicine);
		$this->entityManager->flush();
	}

	public function tearDown() {
		$this->entityManager->remove($this->medicalFile);
		$this->entityManager->remove($this->medicine);
		$this->entityManager->flush();
	}

	/**
	 * Returns a simple treatment
	 * @return LifeLab\RestBundle\Entity\Treatment A simple treatment object
	 */
	private function getTreatment() {
		$treatment = new Treatment();
		$treatment->setDate(new \DateTime());
		$treatment->setFrequency("6 fois par jour");
		$treatment->setQuantity(23);
		$treatment->setDuration(10);
		$serializer = SerializerBuilder::create()->build();
		$treatment->setMedicine($this->medicine);
		return $treatment;
	}
	
	/**
	 * Test if adding a properly formed treatment to a medical file works.
	 */
	public function testPostTreatments() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$result = $client->getResponse()->getContent();
		$newTreatment = $serializer->deserialize($result, 'LifeLab\RestBundle\Entity\Treatment', 'json');
		$this->assertTrue($newTreatment->getId() != NULL);
		$newTreatment = $this->entityManager->merge($newTreatment);
		$this->entityManager->remove($newTreatment);
		$this->entityManager->flush();
	}

	/**
	 * Test if adding a treatment with a date before today expectedly returns a 400 response.
	 */
	public function testPostTreatmentsWithWrongDate() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$yesterday = (new \Datetime);
		$yesterday->sub(new \DateInterval('P1D'));
		$treatment->setDate($yesterday);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test if adding a treatment with a negative quantity expectedly returns a 400 response.
	 */
	public function testPostTreatmentsWithNegativeQuantity() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$treatment->setQuantity(-100);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test if adding a treatment with a quantity set to NULL returns a 400 response.
	 */
	public function testPostTreatmentsWithNULLQuantity() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$treatment->setQuantity(NULL);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test if adding a treatment with a frequency set to NULL expectedly returns a 400 response.
	 */
	public function testPostTreatmentsWithNULLFrequency() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$treatment->setFrequency(NULL);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test if adding a treatment with an illegal frequency value expectedly returns a 400 response.
	 */
	public function testPostTreatmentsWithIllegalFrequency() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$treatment->setFrequency(-100);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test if adding a treatment with an illegal duration expectedly returns a 400 response.
	 */
	public function testPostTreatmentsWithIllegalDuration() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$treatment->setDuration(-100);
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test if adding a treatment with a non existing medicine expectedly returns a 400 response.
	 */
	public function testPostTreatmentsWithNonExistingMedicine() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$medJSON = '{"id" : -100}';
		$serializer = SerializerBuilder::create()->build();
		$nonExistingMedicine = $serializer->deserialize($medJSON, 
			'LifeLab\RestBundle\Entity\Medicine', 'json');
		$treatment->setMedicine($nonExistingMedicine);
		$jsonContent = $serializer->serialize($treatment, 'json');

		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}

	/**
	 * Test if adding a treatment with a non existing prescription expectedly returns a 400 response.
	 */
	public function testPostTreatmentsWithNonExistingPrescriptionMedicine() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$prescriptionJSON = '{"id" : -100}';
		$serializer = SerializerBuilder::create()->build();
		$nonExistingPrescription = $serializer->deserialize($prescriptionJSON, 
			'LifeLab\RestBundle\Entity\Prescription', 'json');
		$treatment->setPrescription($nonExistingPrescription);
		$jsonContent = $serializer->serialize($treatment, 'json');

		$client->request('POST', 
			'/files/' . $this->medicalFile->getId() . '/treatments',
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
	}
}
