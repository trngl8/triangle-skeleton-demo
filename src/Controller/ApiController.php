<?php

namespace App\Controller;

use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: "/api/topic", name: "api_topic_")]
class ApiController extends AbstractController
{
    private const API_GROUP = 'show_topics_api';
    private const API_FORMAT = 'json';

    private static array $contentType = ["Content-type" => "application/json"];
    private static array $apiContext = ['groups' => self::API_GROUP];

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $topics = $doctrine->getRepository(Topic::class)->findAll();
        $json = $this->serializer->serialize($topics, self::API_FORMAT, self::$apiContext);

        return new JsonResponse($json, 200, self::$contentType, true);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function post(Request $request): Response
    {
        $result = ['result' => 'ok'];
        $json = $this->serializer->serialize($result, self::API_FORMAT);

        return new JsonResponse($json, Response::HTTP_CREATED, self::$contentType, true);
    }

    #[Route('/{id}', name: 'get', methods: ['GET', 'HEAD'])]
    public function get(int $id, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $result = $doctrine->getRepository(Topic::class)->find($id);
        $json = $this->serializer->serialize($result, self::API_FORMAT, self::$apiContext);

        return new JsonResponse($json, Response::HTTP_OK, self::$contentType, true);
    }
}
