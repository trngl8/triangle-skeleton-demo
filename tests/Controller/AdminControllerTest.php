<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testAdminSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();

    }
}
