<?php

namespace App\Controller\Admin;

use App\Button\LinkToRoute;
use App\Entity\Card;
use App\Form\Admin\CardAdminType;
use Deployer\Component\PharUpdate\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/card', name: 'admin_card_')]
class CardController extends AbstractController
{
    private $doctrine;

    private $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $this->doctrine->getRepository(Card::class);
    }

    #[Route('', name: 'index')]
    public function index() : Response
    {
        $items = $this->repository->findBy([]);

        $button = new LinkToRoute('project_add', 'button.add');

        if(sizeof($items) === 0) {
            $this->addFlash('warning', 'flash.warning.no_items');
        }

        return $this->render('card/admin/index.html.twig', [
            'items' => $items,
            'button' => $button
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $card = new Card();
        $form = $this->createForm(CardAdminType::class, $card);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->doctrine->getManager()->persist($card);

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        sprintf("%s/%s/%s", $this->getParameter('upload_directory'), 'cards', $card->getId()),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $card->setFilename($newFilename);
            }

            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'flash.success.created');

            return $this->redirectToRoute('admin_card_index');
        }

        return $this->render('card/admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST', 'HEAD'] )]
    public function edit(Request $request, int $id) : Response
    {
        $card =$this->repository->find($id);

        if(!$card) {
            throw new NotFoundHttpException(sprintf("Card %d not found", $id));
        }

        $form = $this->createForm(CardAdminType::class, $card);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        sprintf("%s/%s/%s", $this->getParameter('upload_directory'), 'cards', $card->getId()),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $card->setFilename($newFilename);
            }

            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'flash.success.updated');

            $nextAction = 'admin_card_index';

//            $nextAction = $form->get('saveAndAdd')->isClicked()
//                ? 'admin_project_add'
//                : 'admin_project_index';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('card/admin/edit.html.twig', [
            'item' => $card,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET', 'POST', 'HEAD'] )]
    public function show(int $id, Request $request) : Response
    {
        $card = $this->repository->find($id);

        return $this->render('card/admin/show.html.twig', [
            'item' => $card,
        ]);
    }

    #[Route('/{id}/remove', name: 'remove', methods: ['GET', 'POST', 'HEAD'] )]
    public function remove(Card $item, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('remove', $submittedToken)) {
            //TODO: remove images
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($item);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.removed');

            return $this->redirectToRoute('admin_card_index');
        }

        return $this->render('card/admin/remove.html.twig', [
            'item' => $item,
        ]);
    }
}
