<?php

namespace System\Modules\Article\Security;

use System\Modules\Account\Account;
use System\Modules\Article\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticleVoter extends Voter
{
    public const MODIFY = 'ARTICLE_MODIFY';
    public const REMOVE = 'ARTICLE_REMOVE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MODIFY, self::REMOVE])
            && $subject instanceof Article;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Account) {
            return false;
        }

        /** @var Article $article */
        $article = $subject;

        return match ($attribute) {
            self::MODIFY => $this->canModify($article, $user),
            self::REMOVE => $this->canRemove($article, $user),
            default => false,
        };
    }

    private function canModify(Article $article, Account $user): bool
    {
        return $article->getAuthor() === $user || in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

    private function canRemove(Article $article, Account $user): bool
    {
        return $article->getAuthor() === $user || in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
