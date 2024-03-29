<?php

namespace App\Controller\Admin;

use App\Button\LinkToRoute;
use App\Entity\Topic;
use App\Form\Filter\TopicAdminFilter;
use App\Form\Admin\TopicAdminType;
use App\Model\TopicFilter;
use App\Service\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/topic', name: 'admin_topic_')]
class TopicController extends AbstractController
{
    CONST PAGINATOR_COUNT = 20;
    CONST START_PAGE = 1;
    CONST MIN_COUNT = 0;

    private $doctrine;

    private $topicService;

    public function __construct(ManagerRegistry $doctrine, TopicService $topicService)
    {
        $this->doctrine = $doctrine;
        $this->topicService = $topicService;
    }

    #[Route('', name: 'index')]
    public function index(Request $request) : Response
    {
        $filterData = $request->get('filters') ?? [];
        $filtersExpanded = (bool)$filterData;
        $topicsFilter = new TopicFilter($filterData);

        $page = $request->get('page') ?? self::START_PAGE;
        $sort = $request->get('sort', ['id' => 'desc']);

        $paginator = $this->topicService->addCriteria($filterData)->addOrder($sort)->getPaginator($page, self::PAGINATOR_COUNT);
        $c = count($paginator);

        $filters = $this->createForm(TopicAdminFilter::class, $topicsFilter);

        $filters->handleRequest($request);

        if($filters->isSubmitted() && $filters->isValid()) {
            $filterData = $filters->getData();

            if($filters->get('clear')->isClicked()) {
                $this->addFlash('warning', 'flash.warning.filter_cleared');
                return $this->redirectToRoute('admin_topic_index');
            }

            if($filters->get('save')->isClicked()) {
                $this->addFlash('success', sprintf('flash.success.filter_save %d items', $c));
                return $this->redirectToRoute('admin_topic_index');
            }

            if($filters->get('apply')->isClicked()) {
                return $this->redirectToRoute('admin_topic_index', ['filters' => $filterData]);
            }
        }

        if($c === self::MIN_COUNT) {
            $this->addFlash('warning', 'flash.warning.no_items');
        }

        $lastPage = intdiv($c,  self::PAGINATOR_COUNT) + 1;
        if($c % self::PAGINATOR_COUNT === 0) {
            --$lastPage;
        }
        $pages = range(self::START_PAGE, $lastPage);

        return $this->render('topic/admin/index.html.twig', [
            'button' => new LinkToRoute('topic_add', 'button.add'),
            'paginator' => $paginator,
            'count' => $c,
            'page' => $page,
            'pages' => $pages,
            'filters' => $filters->createView(),
            'filters_expanded' => $filtersExpanded,
            'sort' => $sort
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicAdminType::class, $topic);

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

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST', 'HEAD'] )]
    public function edit(Request $request, int $id) : Response
    {
        $topic = $this->doctrine->getRepository(Topic::class)->find($id);

        if(!$topic) {
            throw new NotFoundHttpException(sprintf("Topic %d not found", $id));
        }

        $form = $this->createForm(TopicAdminType::class, $topic);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();

            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.topic_updated');

            $nextAction = 'admin_topic_index';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('topic/admin/edit.html.twig', [
            'item' => $topic,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/remove', name: 'remove', methods: ['GET', 'POST', 'HEAD'] )]
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

    #[Route('/{id}/close', name: 'close', methods: ['GET', 'POST', 'HEAD'] )]
    public function close(Topic $topic, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('remove', $submittedToken)) {
            if($this->topicService->close($topic)) {
                $this->addFlash('success', 'flash.success.closed');

                return $this->redirectToRoute('admin_topic_index');
            }

            $this->addFlash('warning', 'flash.warning.cannot');

        }

        return $this->render('topic/admin/close.html.twig', [
            'item' => $topic,
        ]);
    }

    #[Route('/{id}/run', name: 'run', methods: ['GET', 'POST', 'HEAD'] )]
    public function run(Topic $topic, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('remove', $submittedToken)) {
            if($this->topicService->run($topic)) {
                $this->addFlash('success', 'flash.success.runned');

                return $this->redirectToRoute('admin_topic_index');
            }

            $this->addFlash('warning', 'flash.warning.cannot');

        }

        return $this->render('topic/admin/run.html.twig', [
            'item' => $topic,
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET', 'HEAD'] )]
    public function show(Topic $topic) : Response
    {
        return $this->render('topic/admin/show.html.twig', [
            'item' => $topic,
        ]);
    }

    #[Route('/{id}/change/priority', name: 'change' )]
    public function change(Topic $topic, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('change', $submittedToken)) {

            $this->addFlash('success', 'flash.success.changed');

            $this->topicService->updateWeight($topic, $topic->getPriority() + 1);

            return $this->redirectToRoute('admin_topic_show', ['id' => $topic->getId()]);
        }

        return $this->render('topic/admin/change.html.twig', [
            'item' => $topic,
        ]);
    }

    #[Route('/export', name: 'export' )]
    public function export() : Response
    {
        $list = $this->topicService->export();

        $fp = fopen('php://temp', 'w');

        foreach ($list as $topic) {
            $topic = array_map(function ($value) {
                if($value instanceof \DateTimeInterface) {
                    return $value->format('Y-m-d');
                }
                return $value;
            }, $topic);
            fputcsv($fp, $topic, ';');
        }

        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="topic.csv"');

        return $response;
    }
}
