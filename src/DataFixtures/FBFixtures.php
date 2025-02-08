<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FBFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $fb = new FB();
            $fb->setName('FB ' . $i);
            $fb->setAddress('Address ' . $i);
            $fb->setVisitAt(new \DateTimeImmutable());
            $fb->setTravelbook($this->getReference(TravelbookFixtures::TRAVELBOOK_REFERENCE . $i));

            $manager->persist($fb);
        }
    }
}
