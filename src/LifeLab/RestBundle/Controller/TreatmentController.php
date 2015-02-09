<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Controller\AbstractController;
use LifeLab\RestBundle\Entity\Intake;
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
        $tz = new \DateTimeZone(date_default_timezone_get());
        $intake->getTime()->setTimezone($tz);
        $expectedIntakes  = $treatment->computeExpectedIntakes();
        if (!array_key_exists($intake->getTime()->format('c'), $expectedIntakes)) {
            $statusCode = 400;
            $message = 'Time is not in expected intakes!';
            $view = $this->view($message, $statusCode);
            return $this->handleView($view);   
        }
        $intake->setTreatment($treatment);
        $entityManager = $this->getDoctrine()->getManager();
        try {
            $entityManager->persist($intake);
            $entityManager->flush();
        } catch (\Exception $e) {
            $statusCode = 409;
            $message = 'An intake matching this time and treatment already exists in the database!';
            $view = $this->view($message, $statusCode);
            return $this->handleView($view);
        }
        $statusCode = 200;
        $view = $this->view($intake, $statusCode);
        return $this->handleView($view);
    }

    /**
     * Returns the list of LifeLab\RestBundle\Entity\Intake related to treatement id saved in the database.
     * @param {integer} Id of the LifeLab\RestBundle\Entity\Treatment
     * @return LifeLab\RestBundle\Entity\Treatment[] An array of Intakes
     */
    private function getIntakesTaken($treatmentId) {
    	$repository =  $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Intake');
        return $repository->findByTreatment($treatmentId);
    }

    public function getIntakesAction($id) {
        $treatment = $this->getEntity($id);
        if ($treatment == NULL) {
            throw new NotFoundHttpException('Treatment not found');
        }
        $taken = $this->getIntakesTaken($id);
        $statusCode = 200;
        $view = $this->view($taken, $statusCode);
        return $this->handleView($view);
    }

    public function getIntakesFullAction($id) {
        $treatment = $this->getEntity($id);
        if ($treatment == NULL) {
            throw new NotFoundHttpException('Treatment not found');
        }
        $taken = $this->getIntakesTaken($id);
        $intakes = $treatment->computeExpectedIntakes();
        foreach ($taken as $i) {
            $key = $i->getTime()->format('c');
            $intakes[$key] = $i;
        }
        $statusCode = 200;
        $view = $this->view(array_values($intakes), $statusCode);
        return $this->handleView($view);
    }
}