<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Entity\Check;
use App\Form\CheckType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CheckController extends AbstractController
{
    #[Route('/check', name: 'check_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $topics = $doctrine->getRepository(Check::class)->findBy([]);

        $button = new LinkToRoute('check_add', 'button.add');

        return $this->render('check/index.html.twig', [
            'items' => $topics,
            'button' => $button
        ]);
    }

    #[Route('/check/add', name: 'check_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $check = new Check();
        $form = $this->createForm(CheckType::class, $check);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($check);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.check_created');

            return $this->redirectToRoute('check_index');
        }

        return $this->render('check/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/check/{id}', name: 'check_show', methods: ['GET', 'HEAD'] )]
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $topic = $doctrine->getRepository(Check::class)->find($id);

        if(!$topic) {
            throw new NotFoundHttpException(sprintf("Topic %d not found", $id));
        }

        return $this->render('check/show.html.twig', [
            'item' => $topic
        ]);
    }
}
