<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerPhpTest extends WebTestCase
{
    public function testHomeSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Test Software');
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
        yield ['index'];
        yield ['project'];
        yield ['info'];
    }
}
