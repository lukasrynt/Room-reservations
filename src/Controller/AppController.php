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
     * @Route("/", name="landing_page")
     * @return Response
     */
    public function landingPage(): Response
    {
        return $this->render('landingPage.html.twig');
    }
}