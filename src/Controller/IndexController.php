<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index(Request $request): Response
    {
        $defaultHero = 'scrum';
        $params = $request->query->all();
        return $this->render('index/index.html.twig', [
            'hero' => $params['hero'] ?? $defaultHero,
        ]);
    }
}
