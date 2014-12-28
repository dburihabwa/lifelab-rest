<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Controller\AbstractController;

use LifeLab\RestBundle\Entity\Patient;
use LifeLab\RestBundle\Entity\PatientRepository;
use LifeLab\RestBundle\Form\PatientType;

use LifeLab\RestBundle\Entity\MedicalFile;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("patients")
 */
class PatientController extends AbstractController {

    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Patient');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Patient';
    }

    /**
     * Returns all the patients in the database stripped of their medical file.
     */
    public function getAllEntities(Request $request) {
        $patients = parent::getAllEntities($request);
        foreach ($patients as $patient) {
            $patient->setMedicalFile(NULL);
        }
        return $patients;
    }

    /**
     * Returns the medical file associated to a patient.
     * @param id - id of the patient
     */
    public function getFileAction($id) {
        $patient = $this->getEntity($id);
        if ($patient == NULL) {
            throw new NotFoundHttpException('Patient not found');
        }
        $medicalFile = $patient->getMedicalFile();
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:MedicalFile');
        $medicalFile = $repository->find($medicalFile->getId());
        $statusCode = 200;
        $view = $this->view($medicalFile, $statusCode);
        return $this->handleView($view);
    }
    
    /**
     * Modifies an existing patient
     * @param  request  - Http Request
     * @param  id       - Id of the patient
     */
    public function putAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Patient');
        $patient = $repository->find($id);
        if ($patient == NULL) {
            throw new NotFoundHttpException('Patient not found');
        }
        
        $form = $this->createForm(new PatientType(), $patient, array('csrf_protection' => false, 'method' => 'PUT'));
        $form->handleRequest($request);
        $statusCode = 200;
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();
        } else {
            $statusCode = 500;
        }
        $view = $this->view($patient, $statusCode);
        return $this->handleView($view);
    }

    /**
     * Creates a new Patient
     * @param   Request Http Request
     */
    public function postAction(Request $request) {
        $patient = new Patient();
        $form = $this->createForm(new PatientType(), $patient, array('csrf_protection' => false, 'method' => 'POST'));
        $form->handleRequest($request);
        $statusCode = 200;
        $medicalFile = new MedicalFile();
        $patient->setMedicalFile($medicalFile);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicalFile);
            $em->persist($patient);
            $em->flush();
        } else {
            $statusCode = 500;
        }
        $view = $this->view($patient, $statusCode);
        return $this->handleView($view);
    }
}
