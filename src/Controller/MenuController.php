<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    public function mainMenu(int $max): Response
    {
        $topMenu = [
            'index' => [
                'title' => 'menu.home',
                'route' => 'default_index',
                'url' => '/index',
            ],
            'products' => [
                'title' => 'menu.products',
                'route' => 'app_product_index',
                'url' => '/product',
            ],
            'projects' => [
                'title' => 'menu.projects',
                'route' => 'app_project_index',
                'url' => '/project',
            ],
            'offers' => [
                'title' => 'menu.offers',
                'route' => 'app_offer_index',
                'url' => '/offer',
            ],
            'features' => [
                'title' => 'menu.features',
                'route' => 'topic_index',
                'url' => '/topic',
            ],
        ];

        return $this->render('menu/main_menu.html.twig', [
            'max' => $max,
            'top_menu' => $topMenu
        ]);
    }
}
