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

        $block2 = new Block('list', '/index', 'Meetups list');
        $block2->setController('app_meetups_internal_list');
        $manager->persist($block2);

        $block3 = new Block('features', '/index', 'Active courses');
        $block3->setController('app_product_internal_list');
        $manager->persist($block3);

        $manager->flush();
    }
}
