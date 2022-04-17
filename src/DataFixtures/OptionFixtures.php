<?php

namespace App\DataFixtures;

use App\Entity\Check;
use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $check = new Check();
        $check->setType('simple');
        $check->setTitle('test title');
        $check->setDescription('test description');

        $option = new Option();
        $option->setParent($check);
        $option->setTitle('option title');
        $option->setType('simple');
        $option->setPosition(1);
        $option->setCorrect(true);

        $manager->persist($check);
        $manager->persist($option);

        $manager->flush();
    }
}
