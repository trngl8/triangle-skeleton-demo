<?php

namespace App\Controller\Admin;

use App\Service\Http\TelegramHttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/integration', name: 'admin_integration_')]
class IntegrationController extends AbstractController
{
    private $telegramClient;

    public function __construct(TelegramHttpClient $telegramService)
    {
        $this->telegramClient = $telegramService;
    }

    #[Route('', name: 'index')]
    public function index() : Response
    {
        // TODO get bot ids from the storage
        return $this->render('admin/integration/index.html.twig', [
            'providers' => [
                'telegram' => [
                    $this->telegramClient->getBotId()
                ]
            ]
        ]);
    }

    #[Route('/telegram/{id}/show', name: 'telegram_show')]
    public function provider() : Response
    {
        $me = $this->telegramClient->responseToArray($this->telegramClient->getMe());
        $webhook = $this->telegramClient->responseToArray($this->telegramClient->getWebhookInfo());

        $result = [
            'me' =>  $me,
            'webhook' => $webhook
        ];

        if(!$webhook['url']) {
            //TODO: check url valid


        }

        $updates = $this->telegramClient->responseToArray($this->telegramClient->getUpdates());
dump($updates);
        return $this->render('admin/integration/show.html.twig', [
            'result' => $result,
            'updates' => $updates
        ]);
    }
}
