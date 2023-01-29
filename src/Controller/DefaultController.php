<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Exception\ThemeLayoutNotFoundException;
use App\Repository\ProductRepository;
use App\Repository\TopicRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class DefaultController
{
    private $topicRepository;

    private $productRepository;

    private $twig;

    private $appTheme;

    private $security;

    public function __construct(ProductRepository $productRepository, TopicRepository $topicRepository, Environment $twig,
        Security $security,
        string $appTheme
    ) {
        $this->productRepository = $productRepository;
        $this->topicRepository = $topicRepository;
        $this->twig = $twig;
        $this->security = $security;
        $this->appTheme = $appTheme;
    }

    public function index() : Response
    {
        $user = $this->security->getUser();

        if(!$user) {
            return new RedirectResponse('/login');
        }

        $button1 = new LinkToRoute('default_module', 'button.more', 'primary', 'bi bi-1-circle');
        $button2 = new LinkToRoute('default_action', 'button.subscribe', 'outline-primary', 'bi bi-2-square');
        $button3 = new LinkToRoute('default_action', 'button.light', 'light');

        $products = $this->productRepository->findBy([], ['id' => 'ASC'], 3, 0);
//        $topics = $this->topicRepository->findBy([], ['id' => 'ASC'], 10, 0);
//        $featured = $this->topicRepository->findBy([], ['id' => 'DESC'], 3, 0);

        $templateName = sprintf('%s.html.twig', $this->appTheme); //format, template environment

        try {
            $template = $this->twig->load($templateName);
        } catch (LoaderError $e) {
            throw new ThemeLayoutNotFoundException("Index template not found");
        }

        $content = $template->render([
            'buttons' => [$button1, $button2, $button3],
            'products' => $products,
//            'topics' => $topics,
//            'featured' => $featured
        ]);

        $response ??= new Response();
        $response->setContent($content);

        return $response;

    }
}
