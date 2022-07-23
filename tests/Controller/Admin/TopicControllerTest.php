<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TopicControllerTest extends WebTestCase
{
    public function testAdminSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/topic');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'title.topics');

        $client->request('GET', '/admin/topic/export');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'text/csv; charset=UTF-8');
        $this->assertResponseHeaderSame('content-disposition', 'attachment; filename="topic.csv"');

        //TODO: check file content
    }
}
