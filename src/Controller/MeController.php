<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function start(): Response
    {
        return $this->render('me.html.twig');
    }

    /**
     * @Route("/about")
     */
    public function report(): Response
    {
        return $this->render('about.html.twig');
    }

    /**
     * @Route("/report")
     */
    public function about(): Response
    {
        return $this->render('report.html.twig');
    }
}
