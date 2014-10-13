<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Allergy;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("allergies")
 */
class AllergyController extends FOSRestController
{
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Allergy');
        $allergy = $repository->find($id);
        
        if ($allergy == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($allergy, $statusCode);
        return $this->handleView($view);
    }
}

