<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{

    public function testAdminSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/card');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Items');

    }

    public function testSubmitSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/card/add');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/card/add');

        $crawler = $client->submitForm('Submit', [
            'card_admin[title]' => 'test',
            'card_admin[code]' => 12,
        ]);

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/card/edit/1');

        $client->submitForm('Submit', [
            'card_admin[title]' => 'new name',
            'card_admin[code]' => 34,
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testSubmitFail(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/card/add');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/card/add');

        //TODO: valid node for the form submit
        $crawler = $client->submitForm('Submit', [
            'card_admin[title]' => null,
            'card_admin[code]' => null,
        ]);

        $this->assertResponseIsSuccessful();
    }
}
