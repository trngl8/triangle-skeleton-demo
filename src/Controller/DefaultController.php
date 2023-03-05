<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Exception\ThemeLayoutNotFoundException;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class DefaultController
{
    private $productRepository;

    private $twig;

    private $appTheme;
    private $defaultModule;

    private $security;

    public function __construct(ProductRepository $productRepository, Environment $twig,
        Security $security,
        string $appTheme,
        string $defaultModule,
    ) {
        $this->productRepository = $productRepository;
        $this->twig = $twig;
        $this->security = $security;
        $this->appTheme = $appTheme;
        $this->defaultModule = $defaultModule;
    }

    public function default(Request $request) : Response
    {
        //TODO: check current route
//        if ($request->attributes->get('_route') === 'default' && $this->appTheme === 'default') {
//        }

        $templateName = sprintf('%s/default.html.twig', $this->appTheme);

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Default template not found");
        }

        $user = $this->security->getUser();

        if ($user) {
            //TODO: get from the routing service
            if(in_array('ROLE_ADMIN', $user->getRoles())) {
                return new RedirectResponse('/admin');
            }

            return new RedirectResponse('/index');
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

    public function index() : Response
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
