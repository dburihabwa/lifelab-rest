<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Medicine;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use JMS\Serializer\SerializerBuilder;


class MedicineControllerTest extends WebTestCase {

	private $medicines;
	private $entityManager;
	public static $NAMES = array(
		"abc",
		"labc",
		"labcd"
	);

	public static $SEARCH_URL = '/medicines/search/';

	public function __construct() {

		$this->kernel = new \AppKernel('test', true);
		$this->kernel->boot();
		$this->container = $this->kernel->getContainer();
		$this->entityManager = $this->container->get('doctrine')->getManager();
		$this->medicines = array();
	}

	public function setUp() {
		foreach (self::$NAMES as $name) {
			$medicine = new Medicine();
			$medicine->setName($name);
			$medicine->setShape('CREAM');
			$medicine->setHowToTake('oral');
			$medicine->setDangerLevel(1);
			$this->entityManager->persist($medicine);
			$this->medicines[] = $medicine;
		}
		$this->entityManager->flush();
	}

	public function tearDown() {
		foreach ($this->medicines as $medicine) {
			$this->entityManager->remove($medicine);
		}
		$this->entityManager->flush();
	}

	public function testGetSingleResultStartingWithPattern() {
		$keyword = 'abc';
		$client = static::createClient();
		$client->request('GET',
			self::$SEARCH_URL . $keyword,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			array());
		$response = $client->getResponse();
		$this->assertEquals(200, $response->getStatusCode());
		$result = $client->getResponse()->getContent();
		$serializer = SerializerBuilder::create()->build();
		$medicines = json_decode($result);
		$this->assertEquals(1, count($medicines));
		$this->assertTrue(strpos($medicines[0]->name, $keyword) === 0);
	}
}
