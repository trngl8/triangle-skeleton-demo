<?php

namespace App\DataFixtures;

use App\Entity\Invite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InviteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Invite();
        $profile->setEmail('admin@test.com');
        $profile->setName('Test');
        $profile->setDescription('some descr');
        $manager->persist($profile);
        $manager->flush();
    }
}
