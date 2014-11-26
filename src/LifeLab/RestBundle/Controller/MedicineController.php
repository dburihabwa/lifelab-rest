<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Medicine;

use LifeLab\RestBundle\Controller\AbstractController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("medicines")
 */
class MedicineController extends AbstractController {
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Medicine');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Medicine';
    }
}


