<?php


namespace App\Controller;



use App\Services\RoomService;
use App\Form\Type\RoomType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    private RoomService $roomService;

    /**
     * RoomController constructor.
     * @param RoomService $roomService
     */
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }


    /**
     * @Route("/rooms", name="rooms_index")
     * @return Response
     */
    public function index(): Response
    {
        $rooms = $this->roomService->findAll();
        return $this->render('rooms/index.html.twig', ['rooms' => $rooms]);
    }

    /**
     * @Route("/rooms/{id}", name="room_detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response{
        $room = $this->roomService->find($id);
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
        $room = $this->roomService->find($id);

        if (!$room)
            return $this->render('errors/404.html.twig');

        $form = $this->createForm(RoomType::class, $room)
            ->add('edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->roomService->save($form->getData());
            return $this->redirectToRoute('room_detail', ['id' => $room->getId()]);
        }

        return $this->render('rooms/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}