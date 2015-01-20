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


    public function getTreatmentsAction($id) {
        $medicalFile = $this->getEntity($id);
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('Medical file not found');
        }
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Treatment');
        $treatments = $repository->findByMedicalFile($medicalFile->getId());
        $statusCode = 200;
        $view = $this->view($treatments, $statusCode);
        return $this->handleView($view);
    }

    public function getPrescriptionsAction($id) {
        $medicalFile = $this->getEntity($id);
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('Medical file not found');
        }
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Prescription');
        $prescriptions = $repository->findByMedicalFile($medicalFile->getId());
        $statusCode = 200;
        $view = $this->view($prescriptions, $statusCode);
        return $this->handleView($view);
    }
    
    /**
     * Adds a treatment to a medical file.
     * The controller checks that:
     * <ul>
     *  <li>The date is not set to earlier than today</li>
     *  <li>The quantity, duration and frequency are above 0</li>
     *  <li>The medicine that should be taken exists in the database</li>
     *  <li>The prescription (if present) exists in the database</li>
     * </ul>
     * If one of the condition in this list is not satisfied, the controller returns a 400 repsonse with a message.
     * @param integer  $idI d of the medical file
     * @param Symfony\Component\HttpFoundation\Request $request HTTP request containing all the parameters
     * @return Symfony\Component\HttpFoundation\Response A reponse with the persisted treatment
     */
    public function postTreatmentsAction($id, Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
        $medicalFile = $repository->find($id);
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $json = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $treatment = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\Treatment', 'json');
        $treatment->setMedicalFile($medicalFile);
        if ($treatment->getDate() == NULL) {
            $statusCode = 400;
            $view = $this->view("Date field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        if ($today > $treatment->getDate()) {
            $statusCode = 400;
            $view = $this->view("Date field must be set to today or any time later in the future!", $statusCode);
            return $this->handleView($view);
        }
        $frequency = $treatment->getFrequency();
        if ($frequency == NULL) {
            $statusCode = 400;
            $view = $this->view("Frequency field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        if ($frequency < 0) {
            $statusCode = 400;
            $view = $this->view("Frequency field must a positive value!", $statusCode);
            return $this->handleView($view);
        }
        $qty = $treatment->getQuantity();
        if ($qty == NULL) {
            $statusCode = 400;
            $view = $this->view("Quantity field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        if ($qty <= 0.0) {
            $statusCode = 400;
            $view = $this->view("Quantity field must be a number greater than 0!", $statusCode);
            return $this->handleView($view);
        }
        $duration = $treatment->getDuration();
        if ($duration == NULL) {
            $statusCode = 400;
            $view = $this->view("Duration field must be defined!", $statusCode);
            return $this->handleView($view);
        }
        if ($duration < 0) {
            $statusCode = 400;
            $view = $this->view("Duration field must be a number greater than 0!", $statusCode);
            return $this->handleView($view);
        }
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
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        if ($today > $prescription->getDate()) {
            $statusCode = 400;
            $view = $this->view("Date field must be set to today or any time later in the future!", $statusCode);
            return $this->handleView($view);
        }
        //Retrieve actual doctor
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Doctor');
        $actualDoctor = $repository->find($doctor->getId());
        $prescription->setDoctor($actualDoctor);

        $em = $this->getDoctrine()->getManager();
        $em->persist($prescription);
        $em->flush();
        $statusCode = 200;
        $view = $this->view($prescription, $statusCode);
        return $this->handleView($view);
    }

    public function postAllergiesAction($id, Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
        $medicalFile = $repository->find($id);
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('medical file not found');
        }
        $json = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $allergy = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\Allergy', 'json');
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Allergy');
        $actualAllergy = $repository->find($allergy->getId());
        if ($actualAllergy == NULL) {
            throw new NotFoundHttpException('allergy not found');
        }

        $alreadyExists = false;
        foreach ($medicalFile->getAllergies() as $a) {
            if ($a->getId() === $actualAllergy->getId()) {
                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {
            $medicalFile->addAllergy($actualAllergy);
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicalFile);
            $em->flush();
        }
        $statusCode = 200;

        $view = $this->view($medicalFile, $statusCode);
        return $this->handleView($view);
    }

    public function postIllnessesAction($id, Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
        $medicalFile = $repository->find($id);
        if ($medicalFile == NULL) {
            throw new NotFoundHttpException('medical file not found');
        }
        $json = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $illness = $serializer->deserialize($json, 'LifeLab\RestBundle\Entity\Illness', 'json');
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Illness');
        $actualIllness = $repository->find($illness->getId());
        if ($actualIllness == NULL) {
            throw new NotFoundHttpException('illness not found');
        }

        $alreadyExists = false;
        foreach ($medicalFile->getIllnesses() as $i) {
            if ($i->getId() === $actualIllness->getId()) {
                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {
            $medicalFile->addIllness($actualIllness);
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicalFile);
            $em->flush();
        }

        $statusCode = 200;
        $view = $this->view($medicalFile, $statusCode);
        return $this->handleView($view);
    }
}