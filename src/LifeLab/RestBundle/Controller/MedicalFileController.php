<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\MedicalFile;
use LifeLab\RestBundle\Entity\Medicine;
use LifeLab\RestBundle\Entity\Treatment;

use LifeLab\RestBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\Serializer\SerializerBuilder;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("files")
 */
class MedicalFileController extends AbstractController {
    
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\MedicalFile';
    }
    
    public function postTreatmentsAction($id, Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
        $medicalFile = $repository->find($id);
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $json = $request->getContent();
        print_r($json);
        $serializer = SerializerBuilder::create()->build();
        $treatment = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\Treatment', 'json');
        if ($treatment->getDate() == NULL) {
            $statusCode = 400;
            $view = $this->view("Date field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        if ($treatment->getFrequency() == NULL) {
            $statusCode = 400;
            $view = $this->view("Frequency field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        $treatment->setMedicalFile($medicalFile);
        //Test if medicine exists in database
        $medicine = $treatment->getMedicine();
        if ($medicine) {
            $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Medicine');
            $medicineId = $medicine->getId();
            $actualMedicine = $repository->find($medicineId);
            if (!$actualMedicine) {
                $statusCode = 400;
                $view = $this->view($treatment, $statusCode);
                return $this->handleView($view);
            }
            $treatment->setMedicine($actualMedicine);
        }
        //Test if prescription exists in the database
        $prescription = $treatment->getPrescription();
        if ($prescription) {            
            $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Prescription');
            $prescriptionId = $prescription->getId();
            $actualPrescription = $repository->find($prescriptionId);
            if (!$actualPrescription) {
                $statusCode = 400;
                $view = $this->view($treatment, $statusCode);
                return $this->handleView($view);
            }
            $treatment->setPrescription($actualPrescription);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($treatment);
        $em->flush();
        $statusCode = 200;
        $view = $this->view($treatment, $statusCode);
        return $this->handleView($view);
    }

    public function postPrescriptionsAction($id, Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
        $medicalFile = $repository->find($id);
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $json = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $prescription = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\Prescription', 'json');
        $prescription->setMedicalFile($medicalFile);
        $doctor = $prescription->getDoctor();
        if ($doctor == NULL) {
            $statusCode = 400;
            $view = $this->view("Doctor field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        if ($prescription->getDate() == NULL) {
            $statusCode = 400;
            $view = $this->view("Date field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        //Retrieve actual doctor
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Doctor');
        $actualDoctor = $repository->find($doctor->getId());
        $prescription->setDoctor($actualDoctor);

        $em = $this->getDoctrine()->getManager();
        $em->persist($prescription);
        $medicalFile->addPrescription($prescription);
        $em->persist($medicalFile);
        $em->flush();
        $statusCode = 200;
        $view = $this->view($prescription, $statusCode);
        return $this->handleView($view);
    }
}