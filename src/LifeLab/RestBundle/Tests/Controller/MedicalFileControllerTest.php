<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Treatment;
use LifeLab\RestBundle\Entity\Medicine;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use JMS\Serializer\SerializerBuilder;


class MedicalFileControllerTest extends WebTestCase {

	private function getTreatment() {
		$treatment = new Treatment();
		$treatment->setDate(new \DateTime());
		$treatment->setFrequency("6 fois par jour");
		$treatment->setUnits(23);
		$medJSON = '{
			"id" : 1
		}';
		$serializer = SerializerBuilder::create()->build();
		$medicine = $serializer->deserialize($medJSON, 'LifeLab\RestBundle\Entity\Medicine', 'json');
		$treatment->setMedicine($medicine);
		return $treatment;
	}
	
	public function testPostTreatments() {
		$client = static::createClient();
		$treatment = $this->getTreatment();
		$serializer = SerializerBuilder::create()->build();
		$jsonContent = $serializer->serialize($treatment, 'json');
		$client->request('POST', 
			'/files/1/treatments', 
			array(),
			array(),
			array('Content-Type' => 'application/json'),
			$jsonContent);
		$result = $client->getResponse()->getContent();
		$newTreatment = $serializer->deserialize($result, 'LifeLab\RestBundle\Entity\Treatment', 'json');
		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
		$this->assertObjectHasAttribute('id', $newTreatment);
	}
}
