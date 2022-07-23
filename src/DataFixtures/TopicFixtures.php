<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TopicFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $topic = new Topic();
        $topic->setType('task');
        $topic->setTitle('Test Task');
        $topic->setDescription('short description');
        $topic->setPriority(1);

        $manager->persist($topic);
        $manager->flush();

        $topic = new Topic();
        $topic->setType('task');
        $topic->setTitle('Another Test Task');
        $topic->setDescription('description shuld be real');
        $topic->setPriority(0);

        $manager->persist($topic);
        $manager->flush();
    }
}
