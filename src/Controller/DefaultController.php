<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Exception\ThemeLayoutNotFoundException;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class DefaultController extends AbstractController
{
    private $productRepository;

    private $twig;

    private $appTheme;
    private $defaultModule;

    public function __construct(ProductRepository $productRepository, Environment $twig,
        string $appTheme,
        string $defaultModule,
    ) {
        $this->productRepository = $productRepository;
        $this->twig = $twig;
        $this->appTheme = $appTheme;
        $this->defaultModule = $defaultModule;
    }

    public function default(Request $request) : Response
    {
        if ($request->cookies->get('THEME_CHOICE')) {
            $this->forward('App\Controller\DefaultController::index', [
                'request' => $request,
            ]);
        }

        $templateName = sprintf('%s/default.html.twig', $this->appTheme);

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Default template not found");
        }

        //TODO: check routes exists
        $button1 = new LinkToRoute('login', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute($this->defaultModule, 'button.subscribe', 'outline-primary', 'bi bi-2-square');

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        $content = $template->render([
            'buttons' => [$button1, $button2],
            'products' => $products,
        ]);

        $response ??= new Response();
        $response->setContent($content);

        return $response;
    }

    public function index(Request $request) : Response
    {
        $templateName = sprintf('%s/index.html.twig', $this->appTheme);

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Index template not found");
        }

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        //TODO: check routes exists
        $button1 = new LinkToRoute('default', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute($this->defaultModule, 'button.subscribe', 'outline-primary', 'bi bi-2-square');
        $button3 = new LinkToRoute($this->defaultModule, 'button.light', 'light');

        $content = $template->render([
            'buttons' => [$button1, $button2, $button3],
            'products' => $products,
        ]);

        $response ??= new Response();
        $response->setContent($content);

        return $response;
    }
}
