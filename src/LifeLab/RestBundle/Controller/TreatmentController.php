<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Controller\AbstractController;
use LifeLab\RestBundle\Entity\Treatment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\Annotations\RouteResource;

use JMS\Serializer\SerializerBuilder;


/**
 * @RouteResource("treatments")
 */
class TreatmentController extends AbstractController {
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Treatment');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Treatment';
    }

    public function postIntakesAction($id, Request $request) {
        $treatment = $this->getEntity($id);
        if ($treatment == NULL) {
            throw new NotFoundHttpException('Treatment not found');
        }
        $json = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $intake = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\Intake', 'json');
        if ($intake == NULL) {
            $statusCode = 400;
            $view = $this->view('intake body is missing!', $statusCode);
            return $this->handleView($view);
        }
        if ($intake->getTime() == NULL) {
            $statusCode = 400;
            $view = $this->view('time is missing!', $statusCode);
            return $this->handleView($view);
        }
        $intake->setTreatment($treatment);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($intake);
        $entityManager->flush();
        $statusCode = 200;
        $view = $this->view($intake, $statusCode);
        return $this->handleView($view);
    }
}