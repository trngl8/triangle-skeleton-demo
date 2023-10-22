<?php

namespace App\Menu;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class Menu
{
    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function getTopMenu(int $max): array
    {
        $menu = [
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
            'calendar' => [
                'title' => 'calendar.title',
                'route' => 'app_calendar_index',
                'url' => '/calendar',
            ],
            'meetup' => [
                'title' => 'menu.meetup',
                'route' => 'app_meetup_index',
                'url' => '/meetup',
            ],
        ];

        $router = $this->router;

        $menu = array_filter($menu, function ($item) use ($router) {
            try {
                $router->generate($item['route']);
                return true;
            } catch (RouteNotFoundException $e) {
                //TODO: log error
                return false;
            }
        });

        return array_slice($menu, 0, $max);
    }
}
