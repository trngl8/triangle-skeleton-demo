<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Exception\ThemeLayoutNotFoundException;
use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class DefaultController extends AbstractController
{
    private $productRepository;

    private $twig;

    private $logger;

    public function __construct(ProductRepository $productRepository, Environment $twig, LoggerInterface $logger) {
        $this->productRepository = $productRepository;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function default(Request $request) : Response
    {
        if ($request->cookies->has('APP_THEME')) {
            $templateName = sprintf('%s.html.twig', $request->cookies->get('APP_THEME'));
            try {
                $template = $this->twig->load($templateName);
            } catch (LoaderError $e) {
                $this->forward('App\Controller\DefaultController::index');
            }
        } else {
            $this->forward('App\Controller\DefaultController::index');
        }

        $button1 = new LinkToRoute('login', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('app_index',  'outline-primary', 'bi bi-2-square');

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        $content = $template->render([
            'buttons' => [$button1, $button2],
            'products' => $products,
        ]);

        return $this->createResponse($content);
    }

    public function index() : Response
    {
        try {
            $template = $this->twig->load('default.html.twig');
        } catch (LoaderError $e) {
            $this->logger->error($e->getMessage());
            throw new ThemeLayoutNotFoundException("Default template not found");
        }

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        $button1 = new LinkToRoute('default', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('default', 'outline-primary', 'bi bi-2-square');
        $button3 = new LinkToRoute('default', 'button.light', 'light');

        $content = $template->render([
            'buttons' => [$button1, $button2, $button3],
            'products' => $products,
        ]);

        return $this->createResponse($content);
    }

    private function createResponse(string $content) : Response
    {
        return (new Response())->setContent($content);
    }
}
