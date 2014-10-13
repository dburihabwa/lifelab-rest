<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Illness;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("illnesses")
 */
class IllnessController extends FOSRestController
{
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Illness');
        $illness = $repository->find($id);
        
        if ($illness == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($illness, $statusCode);
        return $this->handleView($view);
    }
}

