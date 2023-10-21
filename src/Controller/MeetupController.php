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
                ['id' => 1, 'title' => 'Meetup 1', 'plannedAt' => new \DateTimeImmutable()],
                ['id' => 2, 'title' => 'Meetup 2', 'plannedAt' => new \DateTimeImmutable()],
                ['id' => 3, 'title' => 'Meetup 3', 'plannedAt' => new \DateTimeImmutable()],
            ]
        ]);
    }

    #[Route('/{id}/show', name: 'show')]
    public function show(int $id): Response
    {
        return $this->render('meetup/show.html.twig', [
            'meetup' => ['id' => $id, 'title' => sprintf('Meetup %d', $id), 'plannedAt' => new \DateTimeImmutable()],
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $meetupRequest = new MeetupRequest();
        $form = $this->createForm(MeetupType::class, $meetupRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meetup = new Meetup(
                $meetupRequest->title,
                $meetupRequest->plannedDayAt->add($meetupRequest->plannedTimeAt->diff(new \DateTimeImmutable())),
            );

            $this->meetupRepository->add($meetup);

            try {
                $this->meetupRepository->save();
            } catch (\Exception $e) {
                $this->addFlash('error', sprintf('Meetup %s not created!', $meetupRequest->title));
                $this->logger->error($e->getMessage());
                return $this->redirectToRoute('app_meetup_index');
            }

            $this->addFlash('success', sprintf('Meetup %s created!', $meetupRequest->title));

            return $this->redirectToRoute('app_meetup_index');
        }

        return $this->render('meetup/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/join', name: 'join', methods: ['POST'])]
    public function join(int $id, Request $request): Response
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('join', $submittedToken)) {

            $this->addFlash('success', sprintf('You joined to meetup %d!', $id));

            return $this->redirectToRoute('app_meetup_index');
        }

        return $this->redirectToRoute('app_meetup_index');
    }
}
