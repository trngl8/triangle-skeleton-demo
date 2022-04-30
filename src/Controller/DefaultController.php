<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function home() : Response
    {
        return $this->render('default/home.html.twig');
    }

    public function index(ManagerRegistry $doctrine) : Response
    {
        $button = new LinkToRoute('topic_add', 'button.add');

        return $this->render('default/index.html.twig', [
            'button' => $button
        ]);
    }

    public function info() : Response
    {
        return $this->render('default/info.html.twig');
    }
}
