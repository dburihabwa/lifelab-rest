<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Allergy;

class AllergiesControllerTest extends AbstractControllerTest {

	public function __construct() {
		$this->validEntity = new Allergy();
		$this->validEntity->setName('valid Allergy entity');
	}
	public function getType() {
		return 'LifeLab\RestBundle\Entity\Allergy';
	}
	
	public function getURL() {
		return '/allergies';
	}
}