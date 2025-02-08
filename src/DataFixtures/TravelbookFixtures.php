<?php

namespace App\DataFixtures;

use App\Entity\Travelbook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Self_;

class TravelbookFixtures extends Fixture
{
    public const TRAVELBOOK_REFERENCE = 'travelbook_';

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i <= 10; $i++) {
            $travelbook = new Travelbook();
            $travelbook->setTitle('My Trip ' . $i)
                ->setDepartureAt(new \DateTimeImmutable('2025-06-01'))
                ->setComebackAt(new \DateTimeImmutable('2025-06-15'))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($travelbook);
            $this->addReference(Self_::TRAVELBOOK_REFERENCE. $i, $travelbook);
        }

        $manager->flush();
    }
}
