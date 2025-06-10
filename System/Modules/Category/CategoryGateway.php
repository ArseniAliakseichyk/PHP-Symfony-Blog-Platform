<?php

namespace System\Modules\Category;

use Doctrine\ORM\EntityManagerInterface;

class CategoryGateway
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    public function findById(int $id): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    public function createQueryBuilder(string $alias): \Doctrine\ORM\QueryBuilder
    {
        return $this->entityManager->getRepository(Category::class)->createQueryBuilder($alias);
    }
}
