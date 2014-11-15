<?php

namespace LifeLab\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

abstract class AbstractController extends FOSRestController {
    abstract protected function getRepository();
    public function cgetAction() {
        $repository = $this->getDoctrine()->getManager();
        $entities = $repository->findAll();
        $statusCode = 200;
        $view = $this->view($entities, $statusCode);
        return $this->handleView($view);   
    }

    public function getAllAction() {
        return $this->cgetAction();
    }


    protected function getEntity($id) {
        $repository = $this->getRepository();
        return $repository->find($id);   
    }

    public function getAction($id)
    {
        $entity = $this->getEntity($id);
        
        if ($entity == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $statusCode = 200;
        $view = $this->view($entity, $statusCode);
        return $this->handleView($view);
    }

    public function deleteAction($id) {
        $entity = $this->getEntity($id);
        
        if ($entity == NULL) {
            throw new NotFoundHttpException('not found');
        }
        $manager = $this->getDoctrine()->getManager();
        try {
            $manager->remove($entity);
            $manager->flush();
        } catch (Exception $unusedException) {
            $statusCode = 500;
            $message = 'Could not delete resource';
            $view = $this->view($message, $statusCode);
            return $this->handleView($view);
        }

        $statusCode = 200;
        $view = $this->view($entity, $statusCode);
        return $this->handleView($view);
    }
}