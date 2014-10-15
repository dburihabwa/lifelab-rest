<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Allergy;
use LifeLab\RestBundle\Entity\Doctor;
use LifeLab\RestBundle\Entity\Illness;
use LifeLab\RestBundle\Entity\MedicalFile;
use LifeLab\RestBundle\Entity\Medicine;
use LifeLab\RestBundle\Entity\Patient;
use LifeLab\RestBundle\Entity\Prescription;
use LifeLab\RestBundle\Entity\Treatment;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InitController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $allergy = new Allergy();
        $allergy->setName('Noix');
        $em->persist($allergy);
        $em->flush();
        
        $illness = new Illness();
        $illness->setName('Grippe');
        $em->persist($illness);
        $em->flush();
        
        $medicine = new Medicine();
        $medicine->setName('doliprane');
        $medicine->setDosage('500 mg');
        $medicine->setDangerous(1);
        $em->persist($medicine);
        $em->flush();
        
        $medicalFile = new MedicalFile();
        
        $allergies = $medicalFile->getAllergies();
        $medicalFile->addAllergy($allergy);
        
        $illnesses = $medicalFile->getIllnesses();
        $medicalFile->addIllness($illness);
        
        $em->persist($medicalFile);
        $em->flush();
        
        $patient = new Patient();
        $patient->setName('Terry Gilliam');
        $patient->setMedicalFile($medicalFile);
        $em->persist($patient);
        $em->flush();
        
        $doctor = new Doctor();
        $doctor->setName('Dr Jekyll');
        $em->persist($doctor);
        $em->flush();
        
        $prescription = new Prescription();
        $prescription->setDate(new \DateTime());
        $prescription->setMedicalFile($medicalFile);
        $prescription->setDoctor($doctor);
        $em->persist($prescription);
        $em->flush();

        $treatment = new Treatment();
        $treatment->setMedicine($medicine);
        $treatment->setDate(new \DateTime());
        $treatment->setUnits(12);
        $treatment->setFrequency('2 fois par jour');
        $treatment->setMedicalFile($medicalFile);
        $treatment->setPrescription($prescription);
        $em->persist($treatment);
        $em->flush();
        
        
        $response = new Response(json_encode(array('id' => $patient->getId(), 'name' => $patient->getName())));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }
}
