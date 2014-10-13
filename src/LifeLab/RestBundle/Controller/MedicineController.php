<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Medicine;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("medicines")
 */
class MedicineController extends FOSRestController
{
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Medicine');
        $medicine = $repository->find($id);
        
        if ($medicine == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($medicine, $statusCode);
        return $this->handleView($view);
    }
}


