<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Exception\ThemeLayoutNotFoundException;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class DefaultController
{
    private $productRepository;

    private $twig;

    private $appTheme;

    private $security;

    public function __construct(ProductRepository $productRepository, Environment $twig,
        Security $security,
        string $appTheme
    ) {
        $this->productRepository = $productRepository;
        $this->twig = $twig;
        $this->security = $security;
        $this->appTheme = $appTheme;
    }

    public function default() : Response
    {
        $user = $this->security->getUser();

        if ($user) {
            //TODo: redirect to the default module
            //return new RedirectResponse('/login');
        }

        //TODO: check cookie and forward to the index

        $button1 = new LinkToRoute('default_module', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('default_action', 'button.subscribe', 'outline-primary', 'bi bi-2-square');
        $button3 = new LinkToRoute('default_action', 'button.light', 'light');

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);

        $templateName = sprintf('%s/%s.html.twig', $this->appTheme, 'default');

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Default template not found");
        }

        $content = $template->render([
            'buttons' => [$button1, $button2, $button3],
            'products' => $products,
        ]);

        $response ??= new Response();
        $response->setContent($content);

        return $response;

    }

    public function index() : Response
    {
        $templateName = sprintf('%s/%s.html.twig', $this->appTheme, 'index');

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Index template not found");
        }

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);
        $button1 = new LinkToRoute('default_module', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('default_action', 'button.subscribe', 'outline-primary', 'bi bi-2-square');
        $button3 = new LinkToRoute('default_action', 'button.light', 'light');

        $content = $template->render([
            'buttons' => [$button1, $button2, $button3],
            'products' => $products,
        ]);

        $response ??= new Response();
        $response->setContent($content);

        return $response;
    }
}
