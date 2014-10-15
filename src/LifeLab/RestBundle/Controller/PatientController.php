<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Patient;
use LifeLab\RestBundle\Entity\PatientRepository;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("patients")
 */
class PatientController extends FOSRestController
{
    public function getAllAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Patient');
        $patients = $repository->findAll();

        if ($patients == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($patients, $statusCode);
        return $this->handleView($view);
    }

    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Patient');
        $patient = $repository->find($id);
        
        if ($patient == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($patient, $statusCode);
        return $this->handleView($view);
    }
}
