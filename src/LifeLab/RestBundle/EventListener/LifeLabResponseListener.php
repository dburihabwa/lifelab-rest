<?php
namespace LifeLab\RestBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class LifeLabResponseListener {
    public function onKernelResponse(FilterResponseEvent $event) {
        $response = $event->getResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $event->setResponse($response);
    }
}