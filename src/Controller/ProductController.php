<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/product", name: "app_product_")]
class ProductController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ProductRepository $products): Response
    {
        $products = $products->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{alias}/show', name: 'show', methods: ['GET'])]
    public function payment(string $alias, ProductRepository $products, OfferRepository $offerRepository) : Response
    {
        $product = $products->findOneBy(['id' => $alias]);

        $offers = $offerRepository->findBy([], ['id' => 'ASC'], 1, 0);

        return $this->render('product/show.html.twig', [
            'item' => $product,
            'offers' => $offers
        ]);
    }
}
