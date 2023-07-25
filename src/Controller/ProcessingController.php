<?php

namespace App\Controller;

use App\Model\ExternalOrder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProcessingController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    )
    {
    }

    #[Route(path: "/api/order/create", name: "api_order_create", methods: ["POST"])]
    public function createOrder(Request $request) : Response
    {
        $data = $request->request->all()['body'];

        $order = new ExternalOrder($data);

        $errors = $this->validator->validate($order);

        if (count($errors) > 0) {
            return $this->json([
                'status' => 'error',
                'errors' => $errors
            ], 400);
        }

        return $this->json([
            'status' => 'new'
        ]);
    }
}
