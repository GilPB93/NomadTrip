<?php

namespace App\DataFixtures;

use App\Entity\ContactMessage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactMessageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $contactMessage = (new ContactMessage())
                ->setName($faker->firstName)
                ->setEmail($faker->email)
                ->setSubject($faker->sentence)
                ->setMessage($faker->paragraph)
                ->setSentAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now', '+1 year')))
                ->setStatus($faker->randomElement(['unread', 'read']));

            $manager->persist($contactMessage);
        }

        $manager->flush();
    }
}
