<?php

namespace App\Controller;

use App\Entity\Meetup;
use App\Form\MeetupType;
use App\Model\MeetupRequest;
use App\Repository\MeetupRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/meetup', name: 'app_meetup_')]
class MeetupController extends AbstractController
{
    public function __construct(
        private readonly MeetupRepository $meetupRepository,
        private readonly LoggerInterface $logger
    )
    {
    }

    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        return $this->render('meetup/index.html.twig', [
            'meetups' => [
                ['id' => 1, 'title' => 'Meetup 1'],
                ['id' => 1, 'title' => 'Meetup 2'],
                ['id' => 1, 'title' => 'Meetup 3'],
            ]
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(MeetupType::class, new MeetupRequest());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $meetup = new Meetup($data->title);
            $this->meetupRepository->add($meetup);
            try {
                $this->meetupRepository->save();
            } catch (\Exception $e) {
                $this->addFlash('error', sprintf('Meetup %s not created!', $data->title));
                $this->logger->error($e->getMessage());
                return $this->redirectToRoute('app_meetup_index');
            }
            $this->addFlash('success', 'Meetup created!');
            return $this->redirectToRoute('app_meetup_index');
        }

        return $this->render('meetup/create.html.twig', [
            'form' => $form
        ]);
    }
}
