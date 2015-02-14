<?php

namespace LifeLab\RestBundle\Controller;

use JMS\Serializer\SerializerBuilder;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InitController extends Controller {
    public function indexAction() {
        $serializer = SerializerBuilder::create()->build();
        $message = 'API removed! Please use dedicated command';
        $response = new Response($serializer->serialize($message, 'json'));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }
}
