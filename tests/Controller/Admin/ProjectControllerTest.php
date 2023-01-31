<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{

    public function testAdminProductSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/project');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Projects');

    }

    public function testCreateProductSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/project/add');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/project/add');

        $crawler = $client->submitForm('Submit', [
            'project[title]' => 'test',
            'project[type]' => 'type',
            'project[description]' => 'set other description',
        ]);

        $this->assertResponseIsSuccessful();

        //TODO: implement show object IN ADMIN
        //$client->request('GET', '/admin/product/show/1');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/project/edit/1');

        $client->submitForm('Submit', [
            'project[title]' => 'test',
            'project[type]' => 'type',
            'project[description]' => 'set one more description',
        ]);

        $this->assertResponseIsSuccessful();
    }
}
