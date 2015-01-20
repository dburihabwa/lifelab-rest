<?php

/**
  * To work, the CIS.txt fild have to be in the web folder
  */
namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Entity\Allergy;
use LifeLab\RestBundle\Entity\Doctor;
use LifeLab\RestBundle\Entity\Illness;
use LifeLab\RestBundle\Entity\MedicalFile;
use LifeLab\RestBundle\Entity\Medicine;
use LifeLab\RestBundle\Entity\Patient;
use LifeLab\RestBundle\Entity\Prescription;
use LifeLab\RestBundle\Entity\Treatment;

use JMS\Serializer\SerializerBuilder;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InitController extends Controller
{
    public $fields = array('CIS','NAME','FORME','VOIE','STATUS','PROCEDURE','ETAT','DOCUMENT'); 
    public $em;

    function contains($prose, $pattern){
       return strpos(strtolower($prose), utf8_decode(strtolower($pattern))) !== false;
    }

    function addMedicine($medic){
      $data = explode("\t",trim($medic));
      $shape = ' ';
      $howToTake = ' ';

      // On ajoute un dernier champ pour la notice si il n'existe pas dans le fichier
      if (count($data) === 7){
        $data[] = ' ';
      }

      $dataToAdd = array_combine($this->fields,$data);

      // On recherche le type de medicament
      if ($this->contains($dataToAdd['FORME'],'gel') ){ $shape = 'Gel';}
      else if ($this->contains($dataToAdd['FORME'],'gélule') ){ $shape = 'Capsule';}
      else if ($this->contains($dataToAdd['FORME'],'comprimé') ){$shape = 'Pills';}
      else if ($this->contains($dataToAdd['FORME'],'pommade') ){ $shape = 'Ointment';}
      else if ($this->contains($dataToAdd['FORME'],'poudre') ){$shape = 'Powder';}
      else if ($this->contains($dataToAdd['FORME'],'gaz') ){$shape = 'Gas';}
      else if ($this->contains($dataToAdd['FORME'],'plante') ){$shape = 'Plant';}
      else if ($this->contains($dataToAdd['FORME'],'solution') ){$shape = 'Solution';}
      else if ($this->contains($dataToAdd['FORME'],'capsule') ){$shape = 'Pellet';}
      else if ($this->contains($dataToAdd['FORME'],'crème') ){$shape = 'Cream';}
      else if ($this->contains($dataToAdd['FORME'],'sirop') ){$shape = 'Liquid';}	
      else if ($this->contains($dataToAdd['FORME'],'suspension') ){$shape = 'Suspension';}	
      else{ return; }

      // On recherche la voie d'administration
      if ($this->contains($dataToAdd['VOIE'],'orale') || $this->contains($dataToAdd['VOIE'],'buccale') ){ $howToTake = 'Oral';}
      else if ($this->contains($dataToAdd['VOIE'],'orale') ){ $howToTake = 'Oral';}
      else if ($this->contains($dataToAdd['VOIE'],'cutanée') ){ $howToTake = 'Dermal';}
      else if ($this->contains($dataToAdd['VOIE'],'intraveineuse') ){ $howToTake = 'Intraveinous';}
      else if ($this->contains($dataToAdd['VOIE'],'auriculaire') ){ $howToTake = 'Auricular';}
      else if ($this->contains($dataToAdd['VOIE'],'intramusculaire') ){ $howToTake = 'Intramuscular';}
      else if ($this->contains($dataToAdd['VOIE'],'inhalée') ){ $howToTake = 'Inhaled';}
      else if ($this->contains($dataToAdd['VOIE'],'sublinguale') ){ $howToTake = 'Sublingual';}
      else if ($this->contains($dataToAdd['VOIE'],'ophtalmique') ){ $howToTake = 'Ophtalmic';}
      else if ($this->contains($dataToAdd['VOIE'],'périneurale') ){ $howToTake = 'Périneural';}
      else if ($this->contains($dataToAdd['VOIE'],'nasale') ){ $howToTake = 'Nasal';}
      else if ($this->contains($dataToAdd['VOIE'],'endotrachéobronchique') ){ $howToTake = 'Endotracheopulmonary';}
      else{return;};      

      $medicine = new Medicine();
      $medicine->setName($dataToAdd['NAME']);
      $medicine->setShape($shape);
      $medicine->setHowToTake($howToTake);
      $medicine->setDangerLevel(rand(1,3));
      $this->em->persist($medicine);



    }       

    function importMedication(){
      $myfile = fopen('CIS.txt','r');

      while(($buffer = fgets($myfile)) !== false){
        $this->addMedicine($buffer);
      }           
      $this->em->flush();
      fclose($myfile);    
    }

    public function indexAction()
    {
        $this->em = $this->getDoctrine()->getManager();
        
        $allergy = new Allergy();
        $allergy->setName('Noix');
        $this->em->persist($allergy);
        $this->em->flush();
        
        $illness = new Illness();
        $illness->setName('Grippe');
        $this->em->persist($illness);
        $this->em->flush();
        
        $medicine = new Medicine();
        $medicine->setName('bogus');
        $medicine->setShape('Pills');
        $medicine->setHowToTake('Oral');
        $medicine->setDangerLevel(1);
        $this->em->persist($medicine);
        $this->em->flush();
        
        $medicalFile = new MedicalFile();
        
        $allergies = $medicalFile->getAllergies();
        $medicalFile->addAllergy($allergy);
        
        $illnesses = $medicalFile->getIllnesses();
        $medicalFile->addIllness($illness);
        
        $this->em->persist($medicalFile);
        $this->em->flush();
        
        $patient = new Patient();
        $patient->setName('Terry Gilliam');
        $patient->setMedicalFile($medicalFile);
        $this->em->persist($patient);
        $this->em->flush();
        
        $doctor = new Doctor();
        $doctor->setName('Dr Jekyll');
        $this->em->persist($doctor);
        $this->em->flush();
        
        $prescription = new Prescription();
        $prescription->setDate(new \DateTime());
        $prescription->setMedicalFile($medicalFile);
        $prescription->setDoctor($doctor);
        $this->em->persist($prescription);
        $this->em->flush();

        $treatment = new Treatment();
        $treatment->setDate(new \DateTime());
        $treatment->setFrequency(12);
        $qty = 3.0;
        $treatment->setQuantity($qty);
        $treatment->setMedicine($medicine);
        $treatment->setMedicalFile($medicalFile);
        $treatment->setPrescription($prescription);
        $treatment->setDuration(10);
        $this->em->persist($treatment);
        $this->em->flush();
        
        // import medication data
        $this->importMedication();
        
        $serializer = SerializerBuilder::create()->build();
        $response = new Response($serializer->serialize($patient, 'json')); 
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }
}
