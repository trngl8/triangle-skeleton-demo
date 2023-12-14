<?php

namespace App\DataFixtures;

use App\Entity\Block;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlockFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $block = new Block('text', '/', 'Default content here');
        $manager->persist($block);
        $manager->flush();
    }
}
