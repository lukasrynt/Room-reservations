<?php


namespace App\Controller;



use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use RoomType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/rooms/{id}", name="detail_room")
     * @param int $id
     * @return Response
     */
    public function detailRoom(int $id): Response{
        $room = $this->roomRepository->find($id);
        return $this->render('rooms/detail.html.twig', ['room' => $room]);
    }

    /**
     * @Route("/rooms/{id}/edit", name="edit_room")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editRoom(Request $request, int $id): Response{
        $room = $this->roomRepository->find($id);

        $form = $this->createForm(RoomType::class, $room)
            ->add('edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
            return $this->redirectToRoute('detail_room', ['id' => $room->getId()]);
        }

        return $this->render('rooms/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}