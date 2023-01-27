<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{

    public function testAdminOrdersSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/order');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Orders');

    }

    public function testCreateOrderSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/order/add');

        $this->assertResponseIsSuccessful();

        //TODo: check name (translation)
//        $crawler = $client->submitForm('form_submit', [
//            'offer_admin[title]' => 'test',
//            'offer_admin[currency]' => 'UAH',
//            'offer_admin[amount]' => 200,
//        ]);
//
//        $this->assertResponseIsSuccessful();
//
//        $client->request('GET', '/admin/order/show/1');
//
//        $this->assertResponseIsSuccessful();
//
//        $client->request('GET', '/admin/order/edit/1');
//
//        $client->submitForm('form_submit', [
//            'offer_admin[title]' => 'test',
//            'offer_admin[currency]' => 'UAH',
//            'offer_admin[amount]' => 200,
//        ]);
//
//        $this->assertResponseIsSuccessful();
    }
}
