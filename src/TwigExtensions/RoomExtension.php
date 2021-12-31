<?php


namespace App\TwigExtensions;

use App\Entity\Room;
use App\Services\ReservationService;
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
            new TwigFunction('reservedUntil', array($this, 'reservedUntil'))
        ];
    }

    public function reservedUntil(Room $room): string
    {
        $reservation = $this->reservationService->getCurrentReservation($room);
        if (!$reservation)
            return "";
        return $reservation->getTimeTo();
    }
}