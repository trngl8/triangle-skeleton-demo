<?php

namespace App\Tests\Controller;

use App\Model\Subscribe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubscribeControllerTest extends WebTestCase
{
    public function testSubscribeExpiredSuccess() : void
    {
        //TODO: write story about expired subscriptions.

        $subscription = new Subscribe();

        //TODO: create service
        $subscriptionsService = new SubscriptionsService();

        $result = $subscriptionsService->checkValidSubscription($subscription);
        $this->assertTrue($result);
    }

    public function testSubmitSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/subscribe');

         $client->submitForm('form.label.submit', [
            'subscribe[name]' => 'test',
            'subscribe[email]' => 'test@test.com',
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
        yield ['subscribe/success'];
    }
}
