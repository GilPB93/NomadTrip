<?php

namespace App\DataFixtures;

use App\Entity\Travelbook;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TravelbookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager) : void
    {
        $faker = \Faker\Factory::create();

        $users = $manager->getRepository(User::class)->findAll();

        if (empty($users)) {
            throw new \Exception('No users found. Please load UserFixtures first.');
        }

        for ($i = 1; $i <= 20; $i++) {
            $travelbook = new Travelbook();
            $travelbook->setTitle($faker->city)
                ->setDepartureAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', '+1 year')))
                ->setComebackAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 day', '+2 years')))
                ->setFlightNumber($faker->optional()->regexify('[A-Z]{2}[0-9]{3,4}'))
                ->setAccommodation($faker->optional()->company)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setUser($faker->randomElement($users));

            $manager->persist($travelbook);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
