<?php

namespace App\Security;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GroupsVoter extends Voter
{
    const VIEW_ALL = 'view_groups';
    const VIEW = 'view_group';
    const EDIT = 'edit_group';
    const CREATE = 'create_group';
    const DELETE = 'delete_group';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::VIEW_ALL, self::DELETE])) {
            return false;
        }
        if (!($subject instanceof Group || !$subject)) {
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
            case self::CREATE:
                return $this->canCreate($user);
            case self::DELETE:
                return $this->canDelete($user);
            default:
                return false;
        }    }

    private function canViewAll(): bool
    {
        return true;
    }

    private function canView(User $account, Group $group): bool
    {
        if ($account->isAdmin()) {
            return true;
        }

        while($group->getParent() != null) {
            if ($group->getGroupManager() === $account) {
                return true;
            }
            $group = $group->getParent();
        }

        return $account === $group->getGroupManager();

    }

    private function canEdit(User $account, Group $group): bool
    {
        return $this->canView($account, $group);
    }

    private function canCreate(User $account): bool
    {   
        return $account->isAdmin();
    }

    private function canDelete(User $account): bool
    {
        return $account->isAdmin();
    }
}