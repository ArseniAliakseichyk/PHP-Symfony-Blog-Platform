<?php

namespace System\Modules\Auth\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            InteractiveLoginEvent::class => 'onLogin',
        ];
    }

    public function onLogin(InteractiveLoginEvent $event): void
    {
        $event->getRequest()->getSession()->getFlashBag()->add('success', 'Welcome, ' . $event->getAuthenticationToken()->getUser()->getDisplayName() . '!');
    }
}
