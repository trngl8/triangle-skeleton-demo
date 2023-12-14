<?php

namespace App\DataFixtures;

use App\Entity\Block;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlockFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $block1 = new Block('text', '/', 'Default content here');
        $manager->persist($block1);

        $block1 = new Block('list', '/index', 'Meetups list');
        $block1->setController('app_meetups_internal_list');
        $manager->persist($block1);

        $manager->flush();
    }
}
