<?php


namespace App\TwigExtensions;


use App\Entity\Room;
use App\Entity\User;
use App\Services\GroupManagerService;
use App\Services\GroupMemberService;
use App\Services\RoomManagerService;
use App\Services\RoomUserService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoleExtension extends AbstractExtension
{

    private GroupManagerService $groupManagerService;
    private RoomManagerService $roomManagerService;
    private GroupMemberService $groupMemberService;
    private RoomUserService $roomUserService;

    /**
     * RoleExtension constructor.
     * @param GroupManagerService $groupManagerService
     * @param RoomManagerService $roomManagerService
     * @param GroupMemberService $groupMemberService
     * @param RoomUserService $roomUserService
     */
    public function __construct(GroupManagerService $groupManagerService, RoomManagerService $roomManagerService, GroupMemberService $groupMemberService, RoomUserService $roomUserService)
    {
        $this->groupManagerService = $groupManagerService;
        $this->roomManagerService = $roomManagerService;
        $this->groupMemberService = $groupMemberService;
        $this->roomUserService = $roomUserService;
    }


    public function getFunctions()
    {
        return [
            new TwigFunction('hasUserRequestPermission', array($this, 'hasUserRequestPermission')),
            new TwigFunction('hasUserRegistrationPermission', array($this, 'hasUserRegistrationPermission'))
        ];
    }

    public function hasUserRequestPermission(User $user, Room $room): bool
    {
        if ($user->isGroupMember()) {
            $groupMember = $this->groupMemberService->find($user->getId());
            return in_array($room->getRoomGroup(), $groupMember->getGroups()->getValues());
        } elseif ($user->isRoomMember()) {
            $roomUser = $this->roomUserService->find($user->getId());
            return in_array($room, $roomUser->getRooms()->getValues());
        }
        return false;
    }

    public function hasUserRegistrationPermission(User $user, Room $room): bool
    {
        if ($user->isAdmin())
            return true;
        if ($user->isGroupAdmin()) {
            $groupManager = $this->groupManagerService->find($user->getId());
            return in_array($room->getRoomGroup(), $groupManager->getGroups()->getValues());
        } elseif ($user->isRoomAdmin()) {
            $roomManager = $this->roomManagerService->find($user->getId());
            return in_array($room, $roomManager->getManagedRooms()->getValues());
        }
        return false;
    }
}