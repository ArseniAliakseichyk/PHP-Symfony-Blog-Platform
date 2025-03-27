<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\DoctrineEventSubscriber;
use Doctrine\ORM\Events;

class PostSubscriber implements DoctrineEventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
        ];
    }

    public function postPersist(PostPersistEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Post) {
        }
    }
}