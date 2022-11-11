<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferControllerTest extends WebTestCase
{
    public function testIndex() : void
    {
        $client = static::createClient();
        $client->request('GET', '/offer');

        $this->assertResponseIsSuccessful();
    }
}
