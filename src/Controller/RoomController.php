<?php


namespace App\Controller;



use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\RoomType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    private RoomRepository $roomRepository;
    private EntityManagerInterface $entityManager;

    /**
     * RoomController constructor.
     * @param RoomRepository $roomRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RoomRepository $roomRepository, EntityManagerInterface $entityManager)
    {
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/rooms", name="rooms_index")
     * @return Response
     */
    public function index(): Response
    {
        $rooms = $this->roomRepository->findAll();
        return $this->render('rooms/index.html.twig', ['rooms' => $rooms]);
    }

    /**
     * @Route("/rooms/{id}", name="room_detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response{
        $room = $this->roomRepository->find($id);
        if (!$room)
            return $this->render('errors/404.html.twig');
        return $this->render('rooms/detail.html.twig', ['room' => $room]);
    }

    /**
     * @Route("/rooms/{id}/edit", name="room_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response{
        $room = $this->roomRepository->find($id);

        if (!$room)
            return $this->render('errors/404.html.twig');

        $form = $this->createForm(RoomType::class, $room)
            ->add('delete', ButtonType::class, [
                'attr' => ['class' => 'button-base button-danger-outline'],
                'label' => 'Delete'
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
            return $this->redirectToRoute('room_detail', ['id' => $room->getId()]);
        }

        return $this->render('rooms/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}