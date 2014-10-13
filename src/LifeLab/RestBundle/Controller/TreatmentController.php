<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Treatment;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("treatments")
 */
class TreatmentController extends FOSRestController
{
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Treatment');
        $treatment = $repository->find($id);
        
        if ($treatment == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($treatment, $statusCode);
        return $this->handleView($view);
    }
}



