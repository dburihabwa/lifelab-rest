<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Treatment;

use LifeLab\RestBundle\Controller\AbstractController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("treatments")
 */
class TreatmentController extends AbstractController {
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Treatment');
    }
}



