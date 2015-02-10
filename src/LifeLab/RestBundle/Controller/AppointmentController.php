<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Appointment;

use LifeLab\RestBundle\Controller\AbstractController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("appointments")
 */
class AppointmentController extends AbstractController {
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Appointment');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Appointment';
    }

    
}



