<?php

namespace App\Controller\Admin;

use App\Repository\TimeDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/calendar', name: 'admin_calendar_')]
class CalendarAdminController extends AbstractController
{
    public function __construct(
        private readonly TimeDataRepository $timeDataRepository
    )
    {
    }

    #[Route('', name: 'index')]
    public function default() : Response
    {
        $items = $this->timeDataRepository->findAll();

        return $this->render('admin/calendar/default.html.twig', [
            'items' => $items,
        ]);
    }
}