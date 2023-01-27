<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/market', name: 'admin_market_')]
class MarketController extends AbstractController
{
    #[Route('', name: 'default')]
    public function default(): Response
    {
        return $this->render('market/default.html.twig', [
            'controller_name' => 'MarketController',
        ]);
    }
}
