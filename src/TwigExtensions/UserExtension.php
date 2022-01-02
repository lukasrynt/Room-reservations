<?php


namespace App\TwigExtensions;

use App\Entity\Room;
use App\Services\ReservationService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRoleText', array($this, 'getRoleText'))
        ];
    }

    public function getRoleText(string $role): string
    {
        switch ($role) {
            case 'ROLE_GROUP_MANAGER':
                return 'Group manager';
            case 'ROLE_ROOM_MANAGER':
                return 'Room manager';
            case 'ROLE_ADMIN':
                return 'Admin';
            case 'ROLE_USER':
                return 'Common user';
            default:
                return $role;
        }
    }
}