<?php

namespace App\Security;

use App\Entity\Request;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReservationsVoter extends Voter
{
    const VIEW_ALL = 'view_reservations';
    const CREATE = 'create_reservation';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW_ALL, self::CREATE])) {
            return false;
        }
        if ($subject) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // check if the user is logged at all
        if (!$user) {
            return false;
        }

        switch($attribute) {
            case self::VIEW_ALL:
                return $this->canViewAll();
            case self::CREATE:
                return $this->canCreate($user);
            default:
                return false;
        }
    }

    private function canViewAll(): bool
    {
        return true;
    }

    private function canCreate(User $account): bool
    {
        return $account->isAdmin() || $account->isRoomAdmin() || $account->isGroupAdmin();
    }
}