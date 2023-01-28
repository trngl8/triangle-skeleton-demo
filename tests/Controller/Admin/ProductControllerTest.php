<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{

    public function testAdminProductSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/product');
        $this->assertResponseIsSuccessful();
        //TODo: implement translation
        //$this->assertSelectorTextContains('h1', 'Products');

    }

    public function testCreateProductSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/product/add');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/product/add');

        $crawler = $client->submitForm('Submit', [
            'product_admin[title]' => 'test',
            'product_admin[description]' => 'set other description',
        ]);

        $this->assertResponseIsSuccessful();

        //TODO: implement show object IN ADMIN
        //$client->request('GET', '/admin/product/show/1');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/product/edit/1');

        $client->submitForm('Submit', [
            'product_admin[title]' => 'test',
            'product_admin[description]' => 'set one more description',
        ]);

        $this->assertResponseIsSuccessful();
    }
}
