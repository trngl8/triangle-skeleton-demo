<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testAddAnswerToTopicSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
    }
}
