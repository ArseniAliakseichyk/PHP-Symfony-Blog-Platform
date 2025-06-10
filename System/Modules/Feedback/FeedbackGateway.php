<?php

namespace System\Modules\Feedback;

use Doctrine\ORM\EntityManagerInterface;

class FeedbackGateway
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    public function findById(int $id): ?Feedback
    {
        return $this->entityManager->getRepository(Feedback::class)->find($id);
    }

    public function save(Feedback $feedback): void
    {
        $this->entityManager->persist($feedback);
        $this->entityManager->flush();
    }

    public function remove(Feedback $feedback): void
    {
        $this->entityManager->remove($feedback);
        $this->entityManager->flush();
    }
}
