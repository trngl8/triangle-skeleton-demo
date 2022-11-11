<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function home() : Response
    {
        return $this->render('default/home.html.twig');
    }

    public function index() : Response
    {
        $button1 = new LinkToRoute('default_module', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('default_action', 'button.subscribe', 'outline-primary', 'bi bi-2-square');
        $button3 = new LinkToRoute('default_action', 'button.light', 'light');

        return $this->render('default/index.html.twig', [
            'buttons' => [$button1, $button2, $button3]
        ]);
    }

    public function info() : Response
    {
        return $this->render('default/info.html.twig');
    }

    public function features(TopicRepository $repo) : Response
    {
        //use some options

        //TODO: set common features
        //TODO: set features for each module
        //TODO: set features for current user

        $topics = $repo->findBy([], ['createdAt' => 'DESC'], 20);

        return $this->render('default/features.html.twig', [
            'topics' => $topics
        ]);
    }
}
