<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'topic_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $topics = $doctrine->getRepository(Topic::class)->findBy([]);

        return $this->render('topic/index.html.twig', [
            'items' => $topics
        ]);
    }

    #[Route('/topic/add', name: 'topic_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.topic_created');

            return $this->redirectToRoute('topic_index');
        }

        return $this->render('topic/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/topic/{id}', name: 'topic_show', methods: ['GET', 'HEAD'] )]
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $topic = $doctrine->getRepository(Topic::class)->find($id);

        return $this->render('topic/show.html.twig', [
            'item' => $topic
        ]);
    }
}