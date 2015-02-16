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

use FOS\RestBundle\Controller\Annotations\Get;


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
    
  
  /**
   * Search for medicines by name.
   * A limit might be specified using a query string parameter (ie: ?limit=<limit>).
   * A starting point in the results may be specified using a query string parameter for pagination purposes (ie: ?from=<starting point>).
   * @Get("/appointments/search/{keyword}")
   */
  public function searchAction(Request $request, $keyword) {
    $em = $this->getDoctrine()->getManager();
    $limitParameter = $request->query->get('limit');
    $limit = $this->getLimitParameter($request);
    
    $from = $this->getFromParameter($request);
    $query = $em->createQuery('SELECT a FROM LifeLabRestBundle:Appointment a, LifeLabRestBundle:Doctor d WHERE a.doctor = d.id AND d.name LIKE :keyword');
    $query->setParameters(array('keyword' => '%' . $keyword . '%'))
      ->setFirstResult($from);
      
    $appointments = $query->getResult();
    $statusCode = 200;
    $view = $this->view($appointments, $statusCode);
    return $this->handleView($view);
  }
  
  
  public function putAction($id, Request $request) {

    $appointment = $this->getEntity($id);
    if ($appointment == NULL) {
      throw new NotFoundHttpException('appointment not found');
    }
    $json = $request->getContent();
    $serializer = SerializerBuilder::create()->build();
    $newAppointment = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\Appointment', 'json');
    
    $appointment->setConfirmed($newAppointment->getConfirmed());
    $em = $this->getDoctrine()->getManager();
    $em->persist($appointment);
    $em->flush();
    
    $statusCode = 200;

    $view = $this->view($appointment, $statusCode);
    return $this->handleView($view);
  }
  
}



