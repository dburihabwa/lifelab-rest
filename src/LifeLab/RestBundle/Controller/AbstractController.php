<?php

namespace LifeLab\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\Serializer\SerializerBuilder;

use FOS\RestBundle\Controller\FOSRestController;

abstract class AbstractController extends FOSRestController {

    abstract protected function getRepository();

    abstract protected function getEntityName();

    /**
     * Return the short version of the entity's name.
     * @return string Entity's name short version
     */
    protected function getEntityShortName() {
        $entityName = $this->getEntityName();
        $tokens = explode('\\', $entityName);
        $shortName = $tokens[0] . $tokens[1] . ':' . $tokens[count($tokens) - 1];
        return $shortName;
    }

    /**
     * Read a parameter from the request and parse it as an integer.
     * If the parsing fails, the default value passed as a parameter is returned.
     * If no default value is given, the default value is set to 0.
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @param string $name Name of the parameter
     * @param int $defaultValue The value that should be returned if the parameter cannot be found or parsed
     * @return int Parameter value
     */
    protected function getParameter(Request $request, $name, $defaultValue = 0) {
        $parameter = $request->query->get($name);
        $param = $defaultValue;
        if ($parameter) {
            $interpretedValue = intval($parameter, 10);
            if ($interpretedValue > 0) {
                $param = $interpretedValue;
            }
        }
        return $param;
    }

    /**
     * Returns the from parameter from a request.
     * If the parameter cannot be found or parsed, the default value is 0.
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return int From parameter value
     */
    protected function getFromParameter(Request $request) {
        return $this->getParameter($request, 'from', 0);
    }
    
    /**
     * Returns the limit parameter from a request.
     * If the parameter cannot be found or parsed, the default value is 0.
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return int Limit parameter value
     */
    protected function getLimitParameter(Request $request) {
        return $this->getParameter($request, 'limit', 0);
    }

    protected function getAllEntities(Request $request) {
        $from = $this->getFromParameter($request);
        $limit = $this->getLimitParameter($request);
        $entityName = $this->getEntityShortName();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM ' . $entityName . ' e');
        if ($from != 0) {
            $query->setFirstResult($from);
        }
        if ($limit != 0) {
            $query->setMaxResults($limit);
        }
        $entities = $query->getResult();
        return $entities;
    }

    public function getAllAction(Request $request) {
        $entities = $this->getAllEntities($request);
        $statusCode = 200;
        $view = $this->view($entities, $statusCode);
        return $this->handleView($view);
    }


    protected function getEntity($id) {
        $repository = $this->getRepository();
        return $repository->find($id);   
    }

    public function getAction($id) {
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

    public function postAction(Request $request) {
        $json = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($json, $this->getEntityName(), 'json');
        $statusCode = 500;
        $manager = $this->getDoctrine()->getManager();
        try {
            $manager->persist($entity);
            $manager->flush();
        } catch (Exception $unusedException) {
            $statusCode = 500;
            $message = 'Could not post new resource';
            $view = $this->view($message, $statusCode);
            return $this->handleView($view);
        }
        $statusCode = 200;
        $view = $this->view($entity, $statusCode);
        return $this->handleView($view);
    }
}