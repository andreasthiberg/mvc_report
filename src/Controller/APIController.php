<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class APIController extends AbstractController
{
    /**
     * @Route("/card/api/deck",  name="card-api-deck")
     */
    public function deck(): Response
    {
        $deck = $session->get("deck") ?? [];
        $response = new Response();
        $response->setContent(json_encode($deck));
        return $response;
    }

        /**
     * @Route("/api/lucky/number")
     */
    public function number(): Response
    {
        $this->number = random_int(0, 100);

        $data = [
            'lucky-number' => $this->number
        ];

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
