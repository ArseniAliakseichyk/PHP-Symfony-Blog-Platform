<?php

namespace System\Modules\Feedback\Security;

use System\Modules\Account\Account;
use System\Modules\Feedback\Feedback;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FeedbackVoter extends Voter
{
    public const MODIFY = 'FEEDBACK_MODIFY';
    public const REMOVE = 'FEEDBACK_REMOVE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MODIFY, self::REMOVE])
            && $subject instanceof Feedback;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Account) {
            return false;
        }

        /** @var Feedback $feedback */
        $feedback = $subject;

        return match ($attribute) {
            self::MODIFY, self::REMOVE => $this->canModifyOrRemove($feedback, $user),
            default => false,
        };
    }

    private function canModifyOrRemove(Feedback $feedback, Account $user): bool
    {
        if ($feedback->getAuthor() === $user) {
            return true;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        return false;
    }
}
