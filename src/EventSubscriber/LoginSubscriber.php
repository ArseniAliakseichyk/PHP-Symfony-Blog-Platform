<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            InteractiveLoginEvent::class => 'onLogin',
        ];
    }

    public function onLogin(InteractiveLoginEvent $event) {}
}
