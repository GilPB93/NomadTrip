<?php

namespace App\DataFixtures;

use App\Entity\Souvenirs;
use App\Entity\Travelbook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SouvenirsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $travelbooks = $manager->getRepository(Travelbook::class)->findAll();
        if (empty($travelbooks)) {
            throw new \Exception('No users found. Please load TravelbookFixtures first.');
        }

        for ($i = 1; $i <= 20; $i++) {
            $souvenir = (new Souvenirs())
                ->setWhat($faker->word)
                ->setForWho($faker->firstName)
                ->setTravelbook($faker->randomElement($travelbooks));
            $manager->persist($souvenir);
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
