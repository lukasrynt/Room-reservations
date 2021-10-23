<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/")
     * @return Response
     */
    public function number(): Response
    {
        return $this->render('users/index.html.twig', []);
    }
}