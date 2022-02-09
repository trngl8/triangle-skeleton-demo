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

    public function testTopicAddSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', 'topic/add');

        $client->submitForm('Submit', [
            'topic[title]' => 'test_title',
            'topic[type]' => 'test_type',
        ]);
        //$client->followRedirect();

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        //$this->assertResponseRedirects();
        //$this->assertSelectorExists('div:contains("There are 2 topics")');
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
        yield ['topic'];
        yield ['topic/add'];
    }
}
