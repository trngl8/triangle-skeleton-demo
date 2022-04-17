<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Entity\Check;
use App\Entity\Option;
use App\Form\CheckType;
use App\Form\OptionType;
use App\Repository\CheckRepository;
use App\Service\CheckService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;

class CheckController extends AbstractController
{
    private $doctrine;

    private $repository;

    private $checkSevice;

    public function __construct(ManagerRegistry $doctrine, CheckService $checkService)
    {
        $this->doctrine = $doctrine;
        $this->checkSevice = $checkService;
        /** @var CheckRepository repository */
        $this->repository = $this->doctrine->getRepository(Check::class);
    }

    #[Route('/check', name: 'check_index')]
    public function index(): Response
    {
        $topics = $this->repository->findBy([]);

        $button = new LinkToRoute('check_add', 'button.add');

        return $this->render('check/index.html.twig', [
            'items' => $topics,
            'button' => $button
        ]);
    }

    #[Route('/check/add', name: 'check_add')]
    public function add(Request $request): Response
    {
        $check = new Check();
        $form = $this->createForm(CheckType::class, $check);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($check);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.check_created');

            return $this->redirectToRoute('check_index');
        }

        return $this->render('check/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/check/show/{id}', name: 'check_show', methods: ['GET', 'POST', 'HEAD'] )]
    public function show(Request $request, int $id): Response
    {
        $check = $this->doctrine->getRepository(Check::class)->find($id);

        if(!$check) {
            throw new NotFoundHttpException(sprintf("Check %d not found", $id));
        }

        $option = new Option();
        $form = $this->createForm(OptionType::class, $option);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //TODO: move to some service or to event listener
            $position = $this->repository->getMaxOptionPosition($check);
            $option->setPosition(++$position);
            $option->setType($check->getType());

            $check->addOption($option);

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.option_created');

            return $this->redirectToRoute('check_show', ['id' => $check->getId()]);
        }

        return $this->render('check/show.html.twig', [
            'item' => $check,
            'form' => $form->createView()
        ]);
    }

    #[Route('/check/run/{id}', name: 'check_run', methods: ['GET', 'POST', 'HEAD'] )]
    public function run(Request $request, int $id): Response
    {
        $check = $this->doctrine->getRepository(Check::class)->find($id);

        $builder = $this->createFormBuilder();

        $options = $check->getOptions()->toArray();
        $choices = array_combine(
            array_map(function($item) {
                return $item->getTitle();
                }, $options),
            array_map(function($item) {
                return $item->getId();
            }, $options)
        );

        $builder->add('features', ChoiceType::class, [
            'label' => $check->getDescription(),
            'choices' => $choices, //TODO: separate keys and values
            'multiple' => $check->getType() === 'multiply', //TODO: set common type
            'expanded' => true,
            'constraints' => [
                new Assert\NotBlank(),
            ]
        ]);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $entityManager = $this->doctrine->getManager();
            $user = $this->getUser();

            //TODO: write different strategies
            if(is_array($data['features'])) {
                foreach ($data['features'] as $feature) {
                    $option =  $this->checkSevice->getOption($feature);
                    $result = $this->checkSevice->createCheckResult($user, $option);
                    $entityManager->persist($result);
                }
            } else {
                $option =  $this->checkSevice->getOption($data['features']);
                $result = $this->checkSevice->createCheckResult($user, $option);
                $entityManager->persist($result);
            }

            $entityManager->flush();

            $this->addFlash('success', 'flash.success.next');

            return $this->redirectToRoute('check_index');
        }

        return $this->render('check/run.html.twig', [
            'item' => $check,
            'form' => $form->createView()
        ]);
    }

    #[Route('/check/remove/{id}', name: 'check_remove', methods: ['GET', 'POST', 'HEAD'] )]
    public function remove(Check $check, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('remove', $submittedToken)) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($check);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.removed');

            return $this->redirectToRoute('check_index');
        }

        return $this->render('check/remove.html.twig', [
            'item' => $check,
        ]);
    }
}
