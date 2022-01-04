<?php


namespace App\TwigExtensions;

use App\Entity\Room;
use App\Services\ReservationService;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoomExtension extends AbstractExtension
{
    private ReservationService $reservationService;

    /**
     * RoomExtension constructor.
     * @param ReservationService $reservationService
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isCurrentlyReserved', array($this, 'isCurrentlyReserved')),
            new TwigFunction('reservedUntil', array($this, 'reservedUntil')),
            new TwigFunction('activeRoomReservations', array($this, 'activeRoomReservations'), array('needs_environment' => true))
        ];
    }

    public function reservedUntil(Room $room): string
    {
        $reservation = $this->reservationService->getCurrentReservation($room);
        if (!$reservation)
            return "";
        return $reservation->getTimeTo();
    }

    public function activeRoomReservations(Environment $environment, Room $room): string
    {
        $reservations = $room->getReservations();
        return $environment->render('reservations/roomReservations.html.twig', ['reservations' => $reservations]);
    }
}