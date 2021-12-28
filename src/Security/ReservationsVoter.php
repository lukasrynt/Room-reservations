<?php

namespace App\Security;

use App\Entity\Request;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReservationsVoter extends Voter
{
    const VIEW_ALL = 'view_reservations';
    const CREATE = 'create_reservation';
    const BOOK_ROOM = 'book_room';
    const APPROVE = 'approve_reservation';
    const REJECT = 'reject_reservation';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW_ALL, self::CREATE, self::BOOK_ROOM, self::APPROVE, self::REJECT])) {
            return false;
        }
        if (!($subject instanceof Room || $subject instanceof Reservation || !$subject)) {
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
            case self::BOOK_ROOM:
                return $this->canBookRoom($user, $subject);
            case self::APPROVE:
                return $this->canApprove($user, $subject);
            case self::REJECT:
                return $this->canReject($user, $subject);
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

    private function canBookRoom(User $account, Room $room): bool
    {
        if ($account->isAdmin()) {
            return true;
        }
        if (in_array($room, $account->getRooms()->getValues())
            || ($room->getGroup() && $account->getGroup() === $room->getGroup())) {
            return true;
        }
        return false;
    }

    private function canApprove(User $account, Reservation $reservation): bool
    {
        return $this->canBookRoom($account, $reservation->getRoom());
    }

    private function canReject(User $account, Reservation $reservation): bool
    {
        return $this->canBookRoom($account, $reservation->getRoom());
    }
}