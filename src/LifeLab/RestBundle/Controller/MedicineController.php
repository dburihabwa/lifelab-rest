<?php

namespace LifeLab\RestBundle\Controller;

use LifeLab\RestBundle\Controller\AbstractController;
use LifeLab\RestBundle\Entity\Medicine;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\RouteResource;

use Symfony\Component\HttpFoundation\Request;


/**
 * @RouteResource("medicines")
 */
class MedicineController extends AbstractController {
    protected function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository('LifeLabRestBundle:Medicine');
    }

    protected function getEntityName() {
        return 'LifeLab\RestBundle\Entity\Medicine';
    }

    /**
     * Search for medicines by name.
     * A limit might be specified using a query string parameter (ie: ?limit=<limit>)
     * @Get("/medicines/search/{keyword}")
     */
    public function searchAction(Request $request, $keyword) {
	    $em = $this->getDoctrine()->getManager();
	    $limitParameter = $request->query->get('limit');
	    $limit = 25;
	    if ($limit) {
		    $interpretedValue = intval($limitParameter, 10);
		    if ($interpretedValue > 0) {
			    $limit = $interpretedValue;
		    }
	    }
	    $query = $em->createQuery('SELECT m FROM LifeLabRestBundle:Medicine m WHERE m.name LIKE :keyword');
	    $query->setParameter('keyword', '%' . $keyword . '%');
	    $query->setMaxResults($limit);
	    $medicines = $query->getResult();
	    $statusCode = 200;
	    $view = $this->view($medicines, $statusCode);
	    return $this->handleView($view);
    }
}


