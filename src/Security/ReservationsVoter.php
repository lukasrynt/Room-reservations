<?php

namespace App\Security;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReservationsVoter extends Voter
{
    const RESERVE = 'reserve';
    const EDIT = 'edit';
    const CREATE = 'create';

    protected function supports(string $attribute, $subject): bool
    {
        if ($attribute != self::RESERVE) {
            return false;
        }
        if (!($subject instanceof Room)) {
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
            case self::RESERVE:
                return $this->canReserve($user, $subject);
            default:
                return false;
        }
    }

    private function canReserve(User $account, Room $room): bool
    {
        if ($account->isAdmin()) {
            return true;
        }
        if (in_array($room, $account->getRooms()->getValues()) || $account->getGroup() === $room->getGroup()) {
            return true;
        }
        return false;
    }
}