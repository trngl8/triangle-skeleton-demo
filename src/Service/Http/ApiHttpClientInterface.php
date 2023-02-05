<?php

namespace App\Service\Http;

interface ApiHttpClientInterface
{
    public function sendRequest(string $command, string $method) : array;
}
