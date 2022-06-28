<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InviteControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUris
     */
    public function testUriSuccess($uri) : void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
    }

    public function getUris() : iterable
    {
        yield 'accept URI' => ['i/test_code'];
    }
}
