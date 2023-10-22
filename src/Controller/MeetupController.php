<?php

namespace App\Controller;

use App\Entity\Meetup;
use App\Form\MeetupType;
use App\Form\ProfileInfoRequestType;
use App\Model\MeetupRequest;
use App\Repository\MeetupRepository;
use App\Service\MeetupService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/meetups', name: 'app_meetups_')]
class MeetupController extends AbstractController
{
    public function __construct(
        private readonly MeetupService $meetupService,
        private readonly MeetupRepository $meetupRepository,
        private readonly LoggerInterface $logger
    )
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $meetups = $this->meetupRepository->findAll();

        return $this->render('meetup/index.html.twig', [
            'meetups' => $meetups
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $meetup = $this->meetupRepository->get($id);

        return $this->render('meetup/show.html.twig', [
            'meetup' => $meetup,
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
                return $this->redirectToRoute('app_meetups_index');
            }

            $this->addFlash('success', sprintf('Meetup %s created!', $meetupRequest->title));

            return $this->redirectToRoute('app_meetups_index');
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
            if (!$this->getUser()) {
                $form = $this->createForm(ProfileInfoRequestType::class);
                return $this->render('meetup/profile.html.twig', [
                    'form' => $form
                ]);
            }

            $meetup = $this->meetupRepository->get($id);
            $this->meetupService->join($meetup, $this->getUser());

            $this->addFlash('success', sprintf('You joined to meetup %d!', $id));

            return $this->redirectToRoute('app_meetups_index');
        }

        $this->addFlash('error', sprintf('You not joined to meetup %d!', $id));

        return $this->redirectToRoute('app_meetups_index');
    }
}
