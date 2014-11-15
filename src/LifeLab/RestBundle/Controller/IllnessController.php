<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Illness;

use LifeLab\RestBundle\Controller\AbstractController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("illnesses")
 */
class IllnessController extends AbstractController {
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Illness');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Illness';
    }
}

