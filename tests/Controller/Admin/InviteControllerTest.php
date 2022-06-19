<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InviteControllerTest extends WebTestCase
{
    public function testAdminSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/invite');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'title.invites');

    }

    public function testSubmitSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/invite/add');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/invite/add');

        //TODO: valid node for the form submit
        $crawler = $client->submitForm('invite_admin_save', [
            'invite_admin[name]' => 'test',
            'invite_admin[email]' => 'test@test.com',
            'invite_admin[description]' => 'set other description',
            //TODO: legal checkboxes
            //'invite_admin[agree]' => true,
            //'invite_admin[know]' => true,
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

        $client->request('GET', '/admin/invite/add');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/invite/add');

        //TODO: valid node for the form submit
        $crawler = $client->submitForm('invite_admin_save', [
            'invite_admin[name]' => null,
            'invite_admin[email]' => null,
            //TODO: legal checkboxes
            //'invite_admin[agree]' => true,
            //'invite_admin[know]' => true,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'title.add_invite');

        //TODO Test form valid
        $this->assertTrue(false);


    }
    
}
