<?php

namespace System\Modules\Account\Security;

use System\Modules\Account\Account;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AccountVoter extends Voter
{
    public const MODIFY = 'ACCOUNT_MODIFY';
    public const REMOVE = 'ACCOUNT_REMOVE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MODIFY, self::REMOVE])
            && $subject instanceof Account;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Account) {
            return false;
        }

        /** @var Account $account */
        $account = $subject;

        return match ($attribute) {
            self::MODIFY, self::REMOVE => $this->canModifyOrRemove($account, $user),
            default => false,
        };
    }

    private function canModifyOrRemove(Account $account, Account $user): bool
    {
        if ($account === $user) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        return false;
    }
}
