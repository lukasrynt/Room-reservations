<?php

namespace App\Security;

use App\Entity\Request;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReservationsVoter extends Voter
{
    const REQUEST = 'request';
    const RESERVE = 'reserve';
    const APPROVE = 'approve';
    const REJECT = 'reject';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::REQUEST, self::APPROVE, self::REJECT, self::RESERVE])) {
            return false;
        }
        if (!($subject instanceof Room || $subject instanceof Request || !$subject)) {
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
            case self::REQUEST:
                return $this->canRequest($user, $subject);
            case self::APPROVE:
                return $this->canApprove($user, $subject);
            case self::REJECT:
                return $this->canReject($user, $subject);
            case self::RESERVE:
                return $this->canReserve($user);
            default:
                return false;
        }
    }

    private function canRequest(User $account, Room $room): bool
    {
        if ($account->isAdmin()) {
            return true;
        }
        if (in_array($room, $account->getRooms()->getValues()) || $account->getGroup() === $room->getGroup()) {
            return true;
        }
        return false;
    }

    private function canApprove(User $account, Request $request): bool
    {
        return $this->canRequest($account, $request->getRoom());
    }

    private function canReject(User $account, Request $request): bool
    {
        return $this->canRequest($account, $request->getRoom());
    }

    private function canReserve(User $account): bool
    {
        return $account->isAdmin() || $account->isRoomAdmin() || $account->isGroupAdmin();
    }
}