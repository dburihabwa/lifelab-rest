<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\MedicalFile;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("files")
 */
class MedicalFileController extends FOSRestController
{   
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
        $medicalFile = $repository->find($id);
        
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($medicalFile, $statusCode);
        return $this->handleView($view);
    }
}    

