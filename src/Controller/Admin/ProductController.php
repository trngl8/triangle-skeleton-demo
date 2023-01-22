<?php

namespace App\Controller\Admin;

use App\Button\LinkToRoute;
use App\Entity\Product;
use App\Form\Admin\ProductAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/product', name: 'admin_product_')]
class ProductController extends AbstractController
{
    private $doctrine;

    private $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $this->doctrine->getRepository(Product::class);
    }

    #[Route('', name: 'index')]
    public function index() : Response
    {
        $items = $this->repository->findBy([]);

        $button = new LinkToRoute('product_add', 'button.add');

        if(sizeof($items) === 0) {
            $this->addFlash('warning', 'flash.warning.no_items');
        }

        return $this->render('product/admin/index.html.twig', [
            'items' => $items,
            'button' => $button
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductAdminType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.product_created');

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('product/admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST', 'HEAD'] )]
    public function show(Request $request, int $id) : Response
    {
        $product =$this->repository->find($id);

        if(!$product) {
            throw new NotFoundHttpException(sprintf("Product %d not found", $id));
        }

        $form = $this->createForm(ProductAdminType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.product_updated');

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_product_add'
                : 'admin_product_index';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('product/admin/edit.html.twig', [
            'item' => $product,
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'POST', 'HEAD'] )]
    public function remove(Product $product, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('remove', $submittedToken)) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.removed');

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('product/admin/remove.html.twig', [
            'item' => $product,
        ]);
    }
}
