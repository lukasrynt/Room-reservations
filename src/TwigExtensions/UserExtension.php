<?php


namespace App\TwigExtensions;

use App\Entity\Room;
use App\Entity\User;
use App\Services\ReservationService;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRoleText', array($this, 'getRoleText')),
            new TwigFunction('activeUserReservations', array($this, 'activeUserReservations'), array('needs_environment' => true))
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

    public function activeUserReservations(Environment $environment, User $user): string
    {
        $reservations = $user->getReservations();
        return $environment->render('reservations/userReservations.html.twig', ['reservations' => $reservations]);
    }
}