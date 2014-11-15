<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Prescription;

use LifeLab\RestBundle\Controller\AbstractController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("prescriptions")
 */
class PrescriptionController extends AbstractController {

    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Prescription');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Prescription';
    }
}


