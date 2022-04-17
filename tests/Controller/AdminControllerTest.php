<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testAdminSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $client->followRedirect();
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();

    }
}
