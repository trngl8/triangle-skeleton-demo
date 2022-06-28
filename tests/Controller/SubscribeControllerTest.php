<?php

namespace App\Tests\Controller;

use App\Model\Subscribe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubscribeControllerTest extends WebTestCase
{
    public function testSubmitSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/subscribe/add');

         $client->submitForm('form.label.submit', [
            'subscribe[name]' => 'test',
            'subscribe[email]' => 'test@example.com',
            'subscribe[agree]' => true,
            'subscribe[know]' => true,
        ]);

        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect('/subscribe/unsucess'));
    }

    /**
     * @dataProvider getUris
     */
    public function testUriSuccess($uri) : void
    {
        $client = static::createClient();
        $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
    }

    public function getUris() : iterable
    {
        yield ['subscribe/verify'];
    }
}
