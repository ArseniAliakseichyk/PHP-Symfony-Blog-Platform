<?php

namespace System\Modules\Account;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'account:create',
    description: 'Creates a new account'
)]
class CreateAccountCommand extends Command
{
    public function __construct(
        private AccountGateway $gateway,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Account email')
            ->addArgument('displayName', InputArgument::REQUIRED, 'Account display name')
            ->addArgument('password', InputArgument::REQUIRED, 'Account password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $account = new Account();
        $account->setEmail($input->getArgument('email'));
        $account->setDisplayName($input->getArgument('displayName'));

        $hashedPassword = $this->passwordHasher->hashPassword(
            $account,
            $input->getArgument('password')
        );

        $account->updateCredentials($hashedPassword);
        $this->gateway->save($account);

        $output->writeln('Account created successfully!');
        return Command::SUCCESS;
    }
}
