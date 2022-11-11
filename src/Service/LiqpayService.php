<?php

namespace App\Service;

use App\Service\Payment\LiqPayAPI;

class LiqpayService
{
    private $apiClient;

    public function __construct(LiqPayAPI $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getFormParams(array $params) : array
    {
        return $this->apiClient->getFormParams($params);
    }

    public function apiCall(string $path, array $params) : array
    {
        return $this->apiClient->api($path, $params);
    }
}
