<?php

namespace App\Controller\Admin;

use App\Button\LinkToRoute;
use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/topic', name: 'admin_topic_')]
class TopicController extends AbstractController
{
    private $doctrine;

    private $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        /** @var TopicRepository repository */
        $this->repository = $this->doctrine->getRepository(Topic::class);
    }

    #[Route('', name: 'index')]
    public function index() : Response
    {
        $items = $this->repository->findBy([]);

        $button = new LinkToRoute('topic_add', 'button.add');

        if(sizeof($items) === 0) {
            $this->addFlash('warning', 'flash.warning.no_items');
        }

        return $this->render('topic/admin/index.html.twig', [
            'items' => $items,
            'button' => $button
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.topic_created');

            return $this->redirectToRoute('admin_topic_index');
        }

        return $this->render('topic/admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST', 'HEAD'] )]
    public function show(Request $request, int $id) : Response
    {
        $topic = $this->doctrine->getRepository(Topic::class)->find($id);

        if(!$topic) {
            throw new NotFoundHttpException(sprintf("Topic %d not found", $id));
        }

        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();

            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.topic_updated');

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_topic_add'
                : 'admin_topic_index';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('topic/admin/edit.html.twig', [
            'item' => $topic,
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'POST', 'HEAD'] )]
    public function remove(Topic $topic, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('remove', $submittedToken)) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.removed');

            return $this->redirectToRoute('admin_topic_index');
        }

        return $this->render('topic/admin/remove.html.twig', [
            'item' => $topic,
        ]);
    }
}
