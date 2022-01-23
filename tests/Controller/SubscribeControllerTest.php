<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubscribeControllerTest extends WebTestCase
{
    public function testSubmitSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/subscribe');

        $crawler = $client->submitForm('form.label.submit', [
            'subscribe[name]' => 'test',
            'subscribe[email]' => 'test@test.com',
            'subscribe[agree]' => true,
            'subscribe[know]' => true,
        ]);

        $this->assertResponseIsSuccessful();
    }

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
        yield ['subscribe/success'];
    }
}