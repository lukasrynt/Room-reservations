<?php

namespace App\Security;

use App\Entity\Building;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BuildingsVoter extends Voter
{
    const VIEW_ALL = 'view_buildings';
    const VIEW = 'view_building';
    const EDIT = 'edit_building';
    const CREATE = 'create_building';
    const DELETE = 'delete_building';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::VIEW_ALL, self::DELETE])) {
            return false;
        }
        if (!($subject instanceof Building || !$subject)) {
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
                return $this->canViewAll($user);
            case self::VIEW:
                return $this->canView($user);
            case self::EDIT:
                return $this->canEdit($user);
            case self::CREATE:
                return $this->canCreate($user);
            case self::DELETE:
                return $this->canDelete($user);
            default:
                return false;
        }
    }

    private function canViewAll(User $account): bool
    {
        return $account->isAdmin();
    }

    private function canView(User $account): bool
    {
        return $this->canViewAll($account);
    }

    private function canCreate(User $account): bool
    {
        return $this->canViewAll($account);
    }

    private function canEdit(User $account): bool
    {
        return $this->canViewAll($account);
    }

    private function canDelete(User $account): bool
    {
        return $this->canViewAll($account);
    }
}