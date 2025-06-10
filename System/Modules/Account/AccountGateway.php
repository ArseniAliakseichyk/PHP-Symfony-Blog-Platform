<?php

namespace System\Modules\Account;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class AccountGateway
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(int $id): ?Account
    {
        return $this->entityManager->getRepository(Account::class)->find($id);
    }

    public function findByEmail(string $email): ?Account
    {
        return $this->entityManager->getRepository(Account::class)->findOneBy(['email' => $email]);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Account::class)->findAll();
    }

    public function save(Account $account): void
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function remove(Account $account): void
    {
        $this->entityManager->remove($account);
        $this->entityManager->flush();
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $account, string $newHashedPassword): void
    {
        if (!$account instanceof Account) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($account)));
        }

        $account->updateCredentials($newHashedPassword);
        $this->save($account);
    }
}
