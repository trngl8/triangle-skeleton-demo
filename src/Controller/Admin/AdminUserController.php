<?php

namespace App\Controller\Admin;

use App\Button\LinkToRoute;
use App\Entity\Card;
use App\Entity\User;
use App\Form\Admin\CardAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/user', name: 'admin_user_')]
class AdminUserController extends AbstractController
{
    private $doctrine;

    private $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $this->doctrine->getRepository(User::class);
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $items = $this->repository->findBy([]);

        //$button = new LinkToRoute('user_add', 'button.add');

        if (sizeof($items) === 0) {
            $this->addFlash('warning', 'flash.warning.no_items');
        }

        return $this->render('user/admin/index.html.twig', [
            'items' => $items,
            //'button' => $button
        ]);
    }
}
