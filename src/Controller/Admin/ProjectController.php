<?php

namespace App\Controller\Admin;

use App\Button\LinkToRoute;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/project', name: 'admin_project_')]
class ProjectController extends AbstractController
{
    private $doctrine;

    private $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        /** @var ProjectRepository repository */
        $this->repository = $this->doctrine->getRepository(Project::class);
    }

    #[Route('', name: 'index')]
    public function index() : Response
    {
        $items = $this->repository->findBy([]);

        $button = new LinkToRoute('project_add', 'button.add');

        if(sizeof($items) === 0) {
            $this->addFlash('warning', 'flash.warning.no_items');
        }

        return $this->render('project/admin/index.html.twig', [
            'items' => $items,
            'button' => $button
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.project_created');

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('project/admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST', 'HEAD'] )]
    public function show(Request $request, int $id) : Response
    {
        $project = $this->doctrine->getRepository(Project::class)->find($id);

        if(!$project) {
            throw new NotFoundHttpException(sprintf("Project %d not found", $id));
        }

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();

            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.project_updated');

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_project_add'
                : 'admin_project_index';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('project/admin/edit.html.twig', [
            'item' => $project,
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'POST', 'HEAD'] )]
    public function remove(Project $project, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('remove', $submittedToken)) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($project);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.removed');

            return $this->redirectToRoute('admin_project_index');
        }

        return $this->render('project/admin/remove.html.twig', [
            'item' => $project,
        ]);
    }
}
