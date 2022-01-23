<?php

namespace App\Controller;

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
        return $this->render('default/index.html.twig');
    }

    public function info() : Response
    {
        return $this->render('default/info.html.twig');
    }
}