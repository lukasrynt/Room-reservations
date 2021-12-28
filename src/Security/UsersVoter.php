<?php

namespace App\Security;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UsersVoter extends Voter
{
    const VIEW_ALL = 'view_users';
    const VIEW = 'view_user';
    const EDIT = 'edit_user';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW_ALL, self::VIEW, self::EDIT])) {
            return false;
        }
        if (!($subject instanceof User || !$subject)) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user) {
            return false;
        }

        switch($attribute) {
            case self::VIEW_ALL:
                return $this->canViewAll();
            case self::VIEW:
                return $this->canView($user, $subject);
            case self::EDIT:
                return $this->canEdit($user, $subject);
            default:
                return false;
        }
    }

    private function canViewAll(): bool
    {
        return true;
    }

    private function canView(User $account, User $user): bool
    {
        if ($account->isAdmin()) {
            return true;
        }
        return $account === $user;
    }

    private function canEdit(User $account, User $user): bool
    {
        return $this->canView($account, $user);
    }
}