<?php

namespace App\Tests\Service;

use App\Entity\Option;
use App\Entity\Result;
use App\Security\User;
use App\Service\CheckService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CheckServiceTest extends KernelTestCase
{
    private $doctrine;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->doctrine = $kernel->getContainer()->get('doctrine');
    }

    public function testCreateCheckResultOptionSuccess() : void
    {
        $checkService = new CheckService($this->doctrine);
        $entityManager = $this->doctrine->getManager();

        $user = new User('test@test.com');
        $option = $entityManager->getRepository(Option::class)->findOneBy([
            'title' => 'option title'
        ]);

        $result = $checkService->createCheckResult($user, $option);

        $this->assertInstanceOf(Result::class, $result);

    }
}
