<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Doctor;

use LifeLab\RestBundle\Controller\AbstractController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("doctors")
 */
class DoctorController extends AbstractController {
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Doctor');
    }
}

