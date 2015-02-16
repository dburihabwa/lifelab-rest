<?php
namespace LifeLab\RestBundle\Tests\Controller;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use JMS\Serializer\SerializerBuilder;


class DummyClass {
	public function getId() {
		return -1;
	}
}

abstract class AbstractControllerTest extends WebTestCase {
	protected $validEntity;
	abstract public function getType();
	abstract public function getURL();

	public function getEntity($id) {
		$client = static::createClient();
		$url = $this->getURL() . '/' . $id;
		$client->request('GET', 
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			NULL);
		if ($client->getResponse()->getStatusCode() !== 200) {
			$exception = new \Exception($client->getResponse()->getContent() . '');
			throw $exception;
		}
		$serializer = SerializerBuilder::create()->build();
		$json = $client->getResponse()->getContent();
		$entity = $serializer->deserialize(
			$json,
			$this->getType(),
			'json');
		return $entity;
	}

	public function postNewEntity($entity) {
		$url = $this->getURL();
		$serializer = SerializerBuilder::create()->build();
		$jsonBody = $serializer->serialize($entity, 'json');
		$client = static::createClient();
		$client->request('POST', 
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonBody);
		if ($client->getResponse()->getStatusCode() !== 200) {
			$exception = new \Exception($client->getResponse()->getContent() . ' : ' . $jsonBody);
			throw $exception;
		}
		$json = $client->getResponse()->getContent();
		$entity = $serializer->deserialize(
			$json,
			$this->getType(),
			'json');
		return $entity;
	}

	public function deleteEntity($id) {
		$url = $this->getURL() . '/' . $id;
		$client = static::createClient();
		$client->request('DELETE', 
			$url,
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			NULL);
		if ($client->getResponse()->getStatusCode() !== 200) {
			$exception = new \Exception($client->getResponse()->getContent() . '');
			throw $exception;
		}
		return TRUE;
	}

	public function testGetWrongId() {
		try {
			$this->getEntity(-1);
		} catch (\Exception $e) {
			$this->assertTrue(TRUE);
			return;
		}
		$this->fail();
	}

	public function testPostWrongId() {
		$dummy = new DummyClass();
		try {
			$this->postNewEntity($dummy);
		} catch (\Exception $e) {
			$this->assertTrue(TRUE);
			return;
		}
		$this->fail();
	}

	public function testDeleteWrongId() {
		try {
			$this->deleteEntity(-1);
		} catch (\Exception $e) {
			$this->assertTrue(TRUE);
			return;
		}
		$this->fail();
	}

	public function testPostEntity() {
		$entity = NULL;
		try {
			$entity = $this->postNewEntity($this->validEntity);
		} catch (\Exception $e) {
			$this->fail($e->getMessage());
			return;
		}
		$this->assertTrue($entity->getid() != NULL);
	}
}