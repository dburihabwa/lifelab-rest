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
      else if ($this->contains($dataToAdd['FORME'],'gélule') ){ $shape = 'Gélules';}
      else if ($this->contains($dataToAdd['FORME'],'comprimé') ){$shape = 'Comprimés';}
      else if ($this->contains($dataToAdd['FORME'],'pommade') ){ $shape = 'Pommade';}
      else if ($this->contains($dataToAdd['FORME'],'poudre') ){$shape = 'Poudre';}
      else if ($this->contains($dataToAdd['FORME'],'gaz') ){$shape = 'Gaz';}
      else if ($this->contains($dataToAdd['FORME'],'plante') ){$shape = 'Plante';}
      else if ($this->contains($dataToAdd['FORME'],'solution') ){$shape = 'Solution';}
      else if ($this->contains($dataToAdd['FORME'],'capsule') ){$shape = 'Capsule';}
      else if ($this->contains($dataToAdd['FORME'],'crème') ){$shape = 'Crème';}
      else if ($this->contains($dataToAdd['FORME'],'sirop') ){$shape = 'Sirop';}	
      else if ($this->contains($dataToAdd['FORME'],'suspension') ){$shape = 'Suspension';}	
      else{ return; }

      // On recherche la voie d'administration
      if ($this->contains($dataToAdd['VOIE'],'orale') || $this->contains($dataToAdd['VOIE'],'buccale') ){ $howToTake = 'Orale';}
      else if ($this->contains($dataToAdd['VOIE'],'orale') ){ $howToTake = 'Orale';}
      else if ($this->contains($dataToAdd['VOIE'],'cutanée') ){ $howToTake = 'Cutanée';}
      else if ($this->contains($dataToAdd['VOIE'],'intraveineuse') ){ $howToTake = 'Intraveineuse';}
      else if ($this->contains($dataToAdd['VOIE'],'auriculaire') ){ $howToTake = 'Auriculaire';}
      else if ($this->contains($dataToAdd['VOIE'],'intramusculaire') ){ $howToTake = 'Intramusculaire';}
      else if ($this->contains($dataToAdd['VOIE'],'inhalée') ){ $howToTake = 'Inhalée';}
      else if ($this->contains($dataToAdd['VOIE'],'sublinguale') ){ $howToTake = 'Sublinguale';}
      else if ($this->contains($dataToAdd['VOIE'],'ophtalmique') ){ $howToTake = 'Ophtalmique';}
      else if ($this->contains($dataToAdd['VOIE'],'périneurale') ){ $howToTake = 'Périneurale';}
      else if ($this->contains($dataToAdd['VOIE'],'nasale') ){ $howToTake = 'Nasale';}
      else if ($this->contains($dataToAdd['VOIE'],'endotrachéobronchique') ){ $howToTake = 'Endotrachéobronchique';}
      else{return;};      

      $medicine = new Medicine();
      $medicine->setName($dataToAdd['NAME']);
      $medicine->setShape($shape);
      $medicine->setHowToTake($howToTake);
      $medicine->setDangerLevel(rand(1,3));
      $this->em->persist($medicine);



    }       

    function importMedication(){
      $myfile = fopen('/home/azureuser/CIS.txt','r');

      while(($buffer = fgets($myfile)) !== false){
        $this->addMedicine($buffer);
      }           
      $this->em->flush();
      fclose($myfile);    
    }

    public function indexAction()
    {
        $this->em = $this->getDoctrine()->getManager();
        
        // $allergy = new Allergy();
        // $allergy->setName('Noix');
        // $em->persist($allergy);
        // $em->flush();
        
        // $illness = new Illness();
        // $illness->setName('Grippe');
        // $em->persist($illness);
        // $em->flush();
        
        // $medicine = new Medicine();
        // $medicine->setName('doliprane');
        // $medicine->setDosage('500 mg');
        // $medicine->setDangerous(1);
        // $em->persist($medicine);
        // $em->flush();
        
        // $medicalFile = new MedicalFile();
        
        // $allergies = $medicalFile->getAllergies();
        // $medicalFile->addAllergy($allergy);
        
        // $illnesses = $medicalFile->getIllnesses();
        // $medicalFile->addIllness($illness);
        
        // $em->persist($medicalFile);
        // $em->flush();
        
        $patient = new Patient();
        $patient->setName('Terry Gilliam');
        // $patient->setMedicalFile($medicalFile);
        // $em->persist($patient);
        // $em->flush();
        
        // $doctor = new Doctor();
        // $doctor->setName('Dr Jekyll');
        // $em->persist($doctor);
        // $em->flush();
        
        // $prescription = new Prescription();
        // $prescription->setDate(new \DateTime());
        // $prescription->setMedicalFile($medicalFile);
        // $prescription->setDoctor($doctor);
        // $em->persist($prescription);
        // $em->flush();

        // $treatment = new Treatment();
        // $treatment->setMedicine($medicine);
        // $treatment->setDate(new \DateTime());
        // $treatment->setUnits(12);
        // $treatment->setFrequency('2 fois par jour');
        // $treatment->setMedicalFile($medicalFile);
        // $treatment->setPrescription($prescription);
        // $em->persist($treatment);
        // $em->flush();
        
	// import des datas
	$this->importMedication();
        
        $response = new Response(json_encode(array('id' => $patient->getId(), 'name' => $patient->getName())));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }
}
