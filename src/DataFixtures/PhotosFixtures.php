<?php

namespace App\DataFixtures;

use App\Entity\Photos;
use App\Entity\Travelbook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PhotosFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $travelbooks = $manager->getRepository(Travelbook::class)->findAll();
        if (empty($travelbooks)) {
            throw new \Exception('No users found. Please load TravelbookFixtures first.');
        }

        for ($i = 1; $i <= 20; $i++) {
            $photo = (new Photos())
                ->setImgUrl($faker->imageUrl())
                ->setAddedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now', '+1 year')))
                ->setTravelbook($faker->randomElement($travelbooks));
            $manager->persist($photo);
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
