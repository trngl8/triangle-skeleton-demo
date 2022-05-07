<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
    }

    public function testLoginLogoutUserSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com'); //TODO: get from data provider

        $client->loginUser($testUser);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', '1.0.1-test');

        $client->request('GET', '/logout');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();

    }
}
