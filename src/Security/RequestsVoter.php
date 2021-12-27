<?php

namespace App\Security;

use App\Entity\Request;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RequestsVoter extends Voter
{
    const VIEW_ALL = 'view_requests';
    const CREATE = 'create_request';
    const APPROVE = 'approve_request';
    const REJECT = 'reject_request';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::APPROVE, self::REJECT, self::VIEW_ALL])) {
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
            case self::VIEW_ALL:
                return $this->canViewAll($user);
            case self::CREATE:
                return $this->canCreate($user, $subject);
            case self::APPROVE:
                return $this->canApprove($user, $subject);
            case self::REJECT:
                return $this->canReject($user, $subject);
            default:
                return false;
        }
    }

    private function canViewAll(User $account): bool
    {
        return $account->isAdmin();
    }

    private function canCreate(User $account, Room $room): bool
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
        return $this->canCreate($account, $request->getRoom());
    }

    private function canReject(User $account, Request $request): bool
    {
        return $this->canCreate($account, $request->getRoom());
    }
}