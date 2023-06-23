<?php

namespace App\Menu;

class Menu
{
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
        ];

        return array_slice($menu, 0, $max);
    }
}
