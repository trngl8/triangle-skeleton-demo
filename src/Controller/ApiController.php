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

    private static array $contentType = ["Content-type" => "application/json"];

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $topics = $doctrine->getRepository(Topic::class)->findAll();
        $json = $serializer->serialize($topics, 'json', ['groups' => self::API_GROUP]);

        return new JsonResponse($json, 200, self::$contentType, true);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function post(Request $request, SerializerInterface $serializer): Response
    {
        //TODO: write $request and check $result
        $result = 'ok';
        $json = $serializer->serialize(['result' => $result], 'json');

        return new JsonResponse($json, 201, ["Content-type" => "application/json"], true);
    }

    #[Route('/{id}', name: 'get', methods: ['GET', 'HEAD'])]
    public function get(int $id, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        //TODO: check 404
        $result = $doctrine->getRepository(Topic::class)->find($id);
        $json = $serializer->serialize($result, 'json', ['groups' => self::API_GROUP]);

        return new JsonResponse($json, 200, self::$contentType, true);
    }
}
