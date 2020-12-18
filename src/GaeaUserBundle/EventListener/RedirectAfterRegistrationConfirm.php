<?php

namespace GaeaUserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class RedirectAfterRegistrationConfirm implements EventSubscriberInterface {
    
    private $router;
    
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    
    public function onRegistrationConfirm(FormEvent $event)
    {
        $url = $this->router->generate('override-confirm-action');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
    
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirm'
        ];
    }
}