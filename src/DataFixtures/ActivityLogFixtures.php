<?php

namespace App\DataFixtures;

use App\Entity\ActivityLog;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActivityLogFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();

        if (empty($users)) {
            throw new \Exception('Aucun utilisateur trouvé. Veuillez charger des utilisateurs avant.');
        }

        for ($i = 0; $i < 20; $i++) {
            $user = $faker->randomElement($users);
            $login = $faker->dateTimeBetween('-30 days', 'now');

            $hasLogout = $faker->boolean(80);

            $logout = null;
            $duration = null;
            if ($hasLogout) {
                $duration = $faker->numberBetween(300, 7200);
                $logout = (clone $login)->modify("+{$duration} seconds");
            }

            $activityLog = (new ActivityLog())
                ->setUser($user)
                ->setLogin($login)
                ->setDurationOfConnection($duration)
                ->setLogout($logout);

            $manager->persist($activityLog);
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
