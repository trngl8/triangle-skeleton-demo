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
    //TODO: explain constants
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
        //TODO: filters on separate flow
        $filterData = $request->get('filters') ?? [];
        $filtersExpanded = (bool)$filterData;
        $topicsFilter = new TopicFilter($filterData);

        $page = $request->get('page') ?? self::START_PAGE;
        $sort = $request->get('sort', ['id' => 'desc']);

        //TODO: validate $sort

        $paginator = $this->topicService->addCriteria($filterData)->addOrder($sort)->getPaginator($page, self::PAGINATOR_COUNT);
        $c = count($paginator);

        $filters = $this->createForm(TopicAdminFilter::class, $topicsFilter);

        $filters->handleRequest($request);

        if($filters->isSubmitted() && $filters->isValid()) {
            $filterData = $filters->getData();

            //TODO: maybe strategy pattern instead if statements

            if($filters->get('clear')->isClicked()) {
                $this->addFlash('warning', 'flash.warning.filter_cleared');
                return $this->redirectToRoute('admin_topic_index');
            }

            if($filters->get('save')->isClicked()) {
                //TODO implement filter storage
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

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST', 'HEAD'] )]
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

            //TODO: choose next action dynamically

            $nextAction = 'admin_topic_index';

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

    #[Route('/close/{id}', name: 'close', methods: ['GET', 'POST', 'HEAD'] )]
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

    #[Route('/run/{id}', name: 'run', methods: ['GET', 'POST', 'HEAD'] )]
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

        //TODO: maybe run should be without confirm
        return $this->render('topic/admin/run.html.twig', [
            'item' => $topic,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET', 'HEAD'] )]
    public function show(Topic $topic) : Response
    {
        return $this->render('topic/admin/show.html.twig', [
            'item' => $topic,
        ]);
    }

    #[Route('/change/{id}/priority', name: 'change' )]
    public function change(Topic $topic, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('change', $submittedToken)) {

            $this->addFlash('success', 'flash.success.changed');

            //TODO: set dynamic weight property
            $this->topicService->updateWeight($topic, $topic->getPriority() + 1);

            return $this->redirectToRoute('admin_topic_show', ['id' => $topic->getId()]);
        }

        return $this->render('topic/admin/change.html.twig', [
            'item' => $topic,
        ]);
    }
}
