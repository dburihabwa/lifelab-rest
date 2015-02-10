<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Appointment;

use LifeLab\RestBundle\Controller\AbstractController;
use LifeLab\RestBundle\Entity\Doctor;
use LifeLab\RestBundle\Entity\Patient;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\Serializer\SerializerBuilder;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

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



