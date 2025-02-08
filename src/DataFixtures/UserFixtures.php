<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\AccountStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public const USER_REFERENCE = 'user';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ){
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // ADD ADMIN USER
        $admin = (new User())
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPseudo($faker->userName)
            ->setRoles(["ROLE_ADMIN"])
            ->setAccountStatus(AccountStatus::ACTIVE)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $admin->setPassword($this->passwordHasher->hashPassword($admin, "AdminPassword@123"));
        $manager->persist($admin);

        // ADD USERS
        for ($i = 1; $i <= 20; $i++) {
            $user = (new User())
                ->setEmail($faker->email)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPseudo($faker->userName)
                ->setAccountStatus(AccountStatus::ACTIVE)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
            $user->setPassword($this->passwordHasher->hashPassword($user, $faker->password));
            $manager->persist($user);
            $this->addReference(self::USER_REFERENCE . $i, $user);
        }
        $manager->flush();
    }
}
