<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setEmail('admin@test.com');
        $profile->setName('Test');
        $profile->setLocale('loc');
        $profile->setTimezone('Timezone\Continent');
        $manager->persist($profile);
        $manager->flush();
    }
}
