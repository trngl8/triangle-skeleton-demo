<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetTopicsSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/topic');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $response->getStatusCode());
        $finishedData = json_decode($response->getContent(), true);
        $this->assertIsArray($finishedData);
    }

    public function testPostTopicsSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/topic');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetSingleTopicSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/topic/1');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
