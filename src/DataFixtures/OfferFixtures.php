<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $offer = new Offer('Test 1', 'UAH', 451);
        $manager->persist($offer);
        $offer =  new Offer('Test 2', 'UAH', 500);
        $manager->persist($offer);
        $offer =  new Offer('Test 3', 'UAH', 1000);
        $manager->persist($offer);

        $manager->flush();
    }
}
