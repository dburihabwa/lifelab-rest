<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Prescription;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("prescriptions")
 */
class PrescriptionController extends FOSRestController
{
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Prescription');
        $prescription = $repository->find($id);
        
        if ($prescription == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($prescription, $statusCode);
        return $this->handleView($view);
    }
}


