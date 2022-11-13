<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/product", name: "app_product_")]
class ProductController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
    }

    #[Route('/{alias}/show', name: 'show', methods: ['GET'])]
    public function payment(string $alias, ProductRepository $products) : Response
    {
        $product = $products->findOneBy(['alias' => $alias]);

        return $this->render('product/show.html.twig', [
            'item' => $product
        ]);
    }
}
