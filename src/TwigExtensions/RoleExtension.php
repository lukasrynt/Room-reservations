<?php


namespace App\TwigExtensions;


use App\Entity\Room;
use App\Entity\User;
use App\Services\GroupManagerService;
use App\Services\RoomManagerService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoleExtension extends AbstractExtension
{

    private GroupManagerService $groupManagerService;
    private RoomManagerService $roomManagerService;

    /**
     * RoleExtension constructor.
     * @param GroupManagerService $groupManagerService
     * @param RoomManagerService $roomManagerService
     */
    public function __construct(GroupManagerService $groupManagerService, RoomManagerService $roomManagerService)
    {
        $this->groupManagerService = $groupManagerService;
        $this->roomManagerService = $roomManagerService;
    }


    public function getFunctions(): array
    {
        return [
            new TwigFunction('hasUserRequestPermission', array($this, 'hasUserRequestPermission')),
            new TwigFunction('hasUserRegistrationPermission', array($this, 'hasUserRegistrationPermission'))
        ];
    }

    public function hasUserRequestPermission(User $user, Room $room): bool
    {
        if ($room->getGroup() === $user->getGroup())
            return true;
        else
            return in_array($room, $user->getRooms()->getValues());
    }

    public function hasUserRegistrationPermission(User $user, Room $room): bool
    {
        if ($user->isAdmin())
            return true;
        elseif ($user->isGroupAdmin()) {
            $groupManager = $this->groupManagerService->find($user->getId());
            return in_array($room->getGroup(), $groupManager->getGroups()->getValues());
        } elseif ($user->isRoomAdmin()) {
            $roomManager = $this->roomManagerService->find($user->getId());
            return in_array($room, $roomManager->getManagedRooms()->getValues());
        }
        return false;
    }
}