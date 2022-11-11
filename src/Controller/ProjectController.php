<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project')]
    public function index(): Response
    {
        $app_navbar = false;

        return $this->render('project/index.html.twig', [
            //'controller_name' => 'ProjectController',
            //'app_navbar' => $app_navbar
        ]);
    }
}
