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
     * A limit might be specified using a query string parameter (ie: ?limit=<limit>).
     * A starting point in the results may be specified using a query string parameter for pagination purposes (ie: ?from=<starting point>).
     * @Get("/medicines/search/{keyword}")
     */
    public function searchAction(Request $request, $keyword) {
	    $em = $this->getDoctrine()->getManager();
	    $limitParameter = $request->query->get('limit');
	    $limit = $this->getLimitParameter($request);
	    if ($limit == 0) {
	    	$limit = 25;
	    }
	    $from = $this->getFromParameter($request);
	    $query = $em->createQuery('SELECT m FROM LifeLabRestBundle:Medicine m WHERE m.name LIKE :keyword');
	    $query->setParameters(array('keyword' => $keyword . '%'))
	    	->setFirstResult($from)
	    	->setMaxResults($limit);
	    $medicines = $query->getResult();
	    $statusCode = 200;
	    $view = $this->view($medicines, $statusCode);
	    return $this->handleView($view);
    }
}


