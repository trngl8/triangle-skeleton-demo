<?php

namespace App\Controller\Admin;

use App\Button\LinkToRoute;
use App\Entity\Invite;
use App\Form\Admin\InviteAdminType;
use App\Form\Filter\InviteAdminFilter;
use App\Service\InviteService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/invite', name: 'admin_invite_')]
class InviteController extends AbstractController
{
    CONST PAGINATOR_COUNT = 2;
    CONST START_PAGE = 1;
    CONST MIN_COUNT = 0;

    private $doctrine;

    private $inviteService;

    public function __construct(ManagerRegistry $doctrine, InviteService $inviteService)
    {
        $this->doctrine = $doctrine;
        $this->inviteService = $inviteService;
    }

    #[Route('', name: 'index')]
    public function index(Request $request) : Response
    {
        $page = $request->get('page') ?? self::START_PAGE;

        $paginator = $this->inviteService->addCriteria([])->getPaginator($page, self::PAGINATOR_COUNT);
        $c = count($paginator);

        $filters = $this->createForm(InviteAdminFilter::class);

        $filters->handleRequest($request);

        if($filters->isSubmitted() && $filters->isValid()) {
            $filterData = $filters->getData();

            if($filters->get('clear')->isClicked()) {
                $this->addFlash('warning', 'flash.warning.filter_cleared');
                return $this->redirectToRoute('admin_invite_index');
            }

            if($filters->get('save')->isClicked()) {
                $this->addFlash('success', sprintf('flash.success.filter_save %d items', $c));
                return $this->redirectToRoute('admin_invite_index');
            }

            if($filters->get('apply')->isClicked()) {
                return $this->redirectToRoute('admin_invite_index', ['filters' => $filterData]);
            }
        }

        if($c === self::MIN_COUNT) {
            $this->addFlash('warning', 'flash.warning.no_items');
        }

        $pages = range(self::START_PAGE, ceil($c / (self::PAGINATOR_COUNT + 1)));

        return $this->render('invite/admin/index.html.twig', [
            'button' => new LinkToRoute('invite_add', 'button.add'),
            'paginator' => $paginator,
            'count' => $c,
            'page' => $page,
            'pages' => $pages,
            'filters' => $filters->createView(),
            'aria_filter_expanded' => true
        ]);
    }
    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $invite = new Invite();
        $form = $this->createForm(InviteAdminType::class, $invite);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($invite);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.invite_created');

            return $this->redirectToRoute('admin_invite_index');
        }

        return $this->render('invite/admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET', 'HEAD'] )]
    public function show(Invite $invite) : Response
    {
        return $this->render('invite/admin/show.html.twig', [
            'item' => $invite,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST', 'HEAD'] )]
    public function edit(Request $request, int $id) : Response
    {
        $invite = $this->doctrine->getRepository(Invite::class)->find($id);

        if(!$invite) {
            throw new NotFoundHttpException(sprintf("Topic %d not found", $id));
        }

        $form = $this->createForm(InviteAdminType::class, $invite);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();

            $entityManager->persist($invite);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.topic_updated');

            return $this->redirectToRoute('admin_invite_index');
        }

        return $this->render('invite/admin/edit.html.twig', [
            'item' => $invite,
            'form' => $form->createView()
        ]);
    }

}
