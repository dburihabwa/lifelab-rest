<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Doctor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("doctors")
 */
class DoctorController extends FOSRestController
{
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Doctor');
        $doctor = $repository->find($id);
        
        if ($doctor == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($doctor, $statusCode);
        return $this->handleView($view);
    }
}

