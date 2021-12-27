<?php

namespace App\Security;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RoomsVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE]))
            return false;
        if (!($subject instanceof Room))
            return false;
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // check if the user is logged at all
        if (!$user)
            return false;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($user, $subject);
            case self::EDIT:
                return $this->canEdit($user, $subject);
            case self::CREATE:
                return $this->canCreate($user);
        }
        return false;
    }

    private function canView(User $account, Room $room): bool
    {
        if ($this->canEdit($account, $room))
            return true;
        return true;
    }

    private function canEdit(User $account, Room $room): bool
    {
        return $this->canCreate($account);
    }

    private function canCreate(User $account): bool
    {
        return $account->isAdmin();
    }
}