<?php

namespace App\DataFixtures;

use App\Entity\Places;
use App\Entity\Travelbook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use function Symfony\Component\Clock\now;

class PlacesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $travelbooks = $manager->getRepository(Travelbook::class)->findAll();
        if (empty($travelbooks)) {
            throw new \Exception('No users found. Please load TravelbookFixtures first.');
        }

        for ($i = 1; $i <= 20; $i++) {
            $place = new Places();
            $place->setName($faker->city)
                ->setAddress($faker->address)
                ->setVisitAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now', '+1 year')))
                ->setTravelbook($faker->randomElement($travelbooks));

            $manager->persist($place);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TravelbookFixtures::class,
        ];
    }
}
