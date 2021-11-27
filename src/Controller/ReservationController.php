<?php


namespace App\Controller;


use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservations", name="reservations_")
 */
class ReservationController extends AbstractController
{
    private ReservationRepository $reservationRepository;
    private EntityManagerInterface $entityManager;

    /**
     * ReservationController constructor.
     * @param ReservationRepository $reservationRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ReservationRepository $reservationRepository, EntityManagerInterface $entityManager)
    {
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/new", name="_new")
     * @return Response
     */
    public function createReservation(): Response
    {
        //todo
    }

}