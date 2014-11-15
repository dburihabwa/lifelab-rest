<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Allergy;

use LifeLab\RestBundle\Controller\AbstractController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("allergies")
 */
class AllergyController extends AbstractController {

    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Allergy');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Allergy';
    }

}

