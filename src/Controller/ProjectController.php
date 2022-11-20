<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/project", name: "app_project_")]
class ProjectController extends AbstractController
{
    private $doctrine;

    private $projects;
    private $offers;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->projects = $this->doctrine->getRepository(Project::class);
        $this->offers = $this->doctrine->getRepository(Offer::class);
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $projects = $this->projects->findBy([], ['startAt' => 'DESC'], 5);

        return $this->render('project/index.html.twig', [
            'items' => $projects,
            'target_path' => 'app_project_join',
        ]);
    }

    #[Route('/{id}/join', name: 'join')]
    public function join(int $id): Response
    {
        $project = $this->projects->find($id);

        if(!$project) {
            throw $this->createNotFoundException();
        }

        $offers = $this->offers->findHumanSupport();

        return $this->render('project/join.html.twig', [
            'item' => $project,
            'offers' => $offers,
        ]);
    }
}
