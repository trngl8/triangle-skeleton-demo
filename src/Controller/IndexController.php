<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index(Request $request): Response
    {
        $defaultHero = 'scrum';
        $params = $request->query->all();

        $form = $this->createFormBuilder()
            ->add('theme', ChoiceType::class, [
                'choices' => [
                    'Default' => 'default',
                    'Dark' => 'dark',
                    'Light' => 'light',
                ],
                'label' => 'Choose theme',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $theme = $form->getData()['theme'];
            $response = $this->redirectToRoute('app_index', ['hero' => $params['hero'] ?? $defaultHero]);
            $response->headers->setCookie(Cookie::create('APP_THEME', $theme));
            return $response;
        }

        return $this->render('index/index.html.twig', [
            'hero' => $params['hero'] ?? $defaultHero,
            'form' => $form->createView(),
        ]);
    }
}
