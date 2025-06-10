<?php

namespace System\Modules\Account;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'account:update-password',
    description: 'Updates an account password'
)]
class UpdateCredentialsCommand extends Command
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
            ->addArgument('email', InputArgument::REQUIRED, 'Account email address')
            ->addArgument('new-password', InputArgument::REQUIRED, 'New password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $account = $this->gateway->findByEmail($input->getArgument('email'));
        if (!$account) {
            $output->writeln('Error: Account not found!');
            return Command::FAILURE;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $account,
            $input->getArgument('new-password')
        );

        $account->updateCredentials($hashedPassword);
        $this->gateway->save($account);

        $output->writeln('Password updated successfully!');
        return Command::SUCCESS;
    }
}
