<?php


namespace App\Controller;


use App\Entity\Room;
use App\Services\RoomService;
use App\Form\Type\RoomType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rooms", name="rooms_")
 */
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
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('view_rooms');
        if (!$this->getUser()) {
            $rooms = $this->roomService->findAllPublic();
        } else {
            $rooms = $this->roomService->filter($request->query->all());
        }
        return $this->render('rooms/index.html.twig', ['rooms' => $rooms]);
    }

    /**
     * @Route("/{id}", name="detail", methods="GET", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response{
        $room = $this->roomService->find($id);
        $this->denyAccessUnlessGranted('view_room', $room);
        if (!$room) {
            return $this->render('errors/404.html.twig');
        }
        return $this->render('rooms/detail.html.twig', ['room' => $room]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response{
        $room = $this->roomService->find($id);

        if (!$room) {
            return $this->render('errors/404.html.twig');
        }

        $this->denyAccessUnlessGranted('edit_room', $room);
        $form = $this->createForm(RoomType::class, $room)
            ->add('delete', ButtonType::class, [
                'attr' => ['class' => 'button-base button-danger-outline'],
                'label' => 'Delete'
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->roomService->save($form->getData());
            $this->addFlash('success', "Room {$room->getName()} was successfully edited.");
            return $this->redirectToRoute('rooms_detail', ['id' => $room->getId()]);
        }

        return $this->render('rooms/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods="DELETE", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $room = $this->roomService->find($id);
        $this->denyAccessUnlessGranted('delete_room', $room);
        if (!$room) {
            return $this->render('errors/404.html.twig');
        } else {
            $this->roomService->delete($room);
            $this->addFlash('success', "Room {$room->getName()} was successfully deleted.");
            return $this->redirectToRoute('rooms_index');
        }
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response {
        $this->denyAccessUnlessGranted('create_room');
        $room = new Room;
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->roomService->save($form->getData());
            $this->addFlash('success', "Room {$room->getName()} was successfully created.");
            return $this->redirectToRoute('rooms_detail', ['id' => $room->getId()]);
        }

        return $this->render('rooms/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}