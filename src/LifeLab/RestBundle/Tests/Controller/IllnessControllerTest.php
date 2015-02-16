<?php

namespace LifeLab\RestBundle\Tests\Controller;

use LifeLab\RestBundle\Entity\Illness;

class IllnessControllerTest extends AbstractControllerTest {

	public function __construct() {
		$this->validEntity = new Illness();
		$this->validEntity->setName('valid illness entity');
	}

	public function getType() {
		return 'LifeLab\RestBundle\Entity\Illness';
	}
	
	public function getURL() {
		return '/illnesses';
	}
}