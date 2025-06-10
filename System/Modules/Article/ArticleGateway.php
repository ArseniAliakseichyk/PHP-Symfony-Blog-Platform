<?php

namespace System\Modules\Article;

use Doctrine\ORM\EntityManagerInterface;

class ArticleGateway
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    public function findById(int $id): ?Article
    {
        return $this->entityManager->getRepository(Article::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Article::class)->findAll();
    }

    public function save(Article $article): void
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    public function remove(Article $article): void
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }
}
