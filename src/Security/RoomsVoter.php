<?php

namespace App\Security;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RoomsVoter extends Voter
{
    const VIEW_ALL = 'view_rooms';
    const VIEW = 'view_room';
    const EDIT = 'edit_room';
    const CREATE = 'create_room';
    const DELETE = 'delete_room';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::VIEW_ALL, self::DELETE])) {
            return false;
        }
        if (!($subject instanceof Room || !$subject)) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if ($user === "") {
            $user = null;
        }

        switch($attribute) {
            case self::VIEW_ALL:
                return $this->canViewAll();
            case self::VIEW:
                return $this->canView($user, $subject);
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

    private function canViewAll(): bool
    {
        return true;
    }


    private function canView(?User $account, Room $room): bool
    {
        if (!$account && $room->getPrivate()) {
            return false;
        }
        return true;
    }

    private function canCreate(?User $account): bool
    {
        if (!$account) {
            return false;
        }
        return $account->isAdmin();
    }

    private function canEdit(?User $account): bool
    {
        return $this->canCreate($account);
    }

    private function canDelete(?User $account): bool
    {
        return $this->canCreate($account);
    }
}