<?php

namespace App\DataFixtures;

use App\Entity\ContactMessage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactMessageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $contactMessage = new ContactMessage();
            $contactMessage->setName('Name ' . $i);
            $contactMessage->setEmail('email' . $i . '@example.com');
            $contactMessage->setSubject('Subject ' . $i);
            $contactMessage->setMessage('Message ' . $i);
            $contactMessage->setSentAt(new \DateTimeImmutable());

            $manager->persist($contactMessage);
        }

        $manager->flush();
    }
}
