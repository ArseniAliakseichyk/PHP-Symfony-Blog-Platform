<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:change-password',
    description: 'Changes user password'
)]
class ChangePasswordCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('new-password', InputArgument::REQUIRED, 'New password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $input->getArgument('email')]);

        if (!$user) {
            $output->writeln('User not found!');
            return Command::FAILURE;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $input->getArgument('new-password')
        );

        $user->setPassword($hashedPassword);
        $this->entityManager->flush();

        $output->writeln('Password changed successfully!');
        return Command::SUCCESS;
    }
}