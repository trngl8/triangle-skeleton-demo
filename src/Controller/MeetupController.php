<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/meetup', name: 'app_meetup_')]
class MeetupController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        return $this->render('meetup/index.html.twig');
    }
}
