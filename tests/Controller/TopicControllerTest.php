<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TopicControllerTest extends WebTestCase
{
    public function testAddAnswerToTopicSuccess() : void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testRemoveAnswerSuccess() : void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testRemoveAnswerFailed() : void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testTopicChangeSuccess() : void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testTopicRemoveSuccess() : void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
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

    public function test404Notfound() : void
    {
        $client = static::createClient();
        $client->request('GET','topic/200');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function getUris() : iterable
    {
        yield ['topic'];
    }
}
