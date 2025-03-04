<?php

namespace App\DataFixtures;

use App\Entity\Travelbook;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DateTimeImmutable;
use Symfony\Component\Filesystem\Filesystem;

class TravelbookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();

        if (empty($users)) {
            throw new \Exception('No users found. Please load UserFixtures first.');
        }

        $filesystem = new Filesystem();
        $uploadDir = __DIR__ . '/../../public/uploads/images/travelbooks/';

        if (!$filesystem->exists($uploadDir)) {
            $filesystem->mkdir($uploadDir, 0777);
        }

        for ($i = 1; $i <= 20; $i++) {
            $departureAt = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now'));
            $comebackAt = DateTimeImmutable::createFromMutable($faker->dateTimeBetween($departureAt->format('Y-m-d H:i:s'), '+1 year'));

            $travelbook = (new Travelbook())
                ->setTitle($faker->city)
                ->setDepartureAt($departureAt)
                ->setComebackAt($comebackAt)
                ->setFlightNumber($faker->optional()->regexify('[A-Z]{2}[0-9]{3,4}'))
                ->setAccommodation($faker->optional()->company)
                ->setUser($faker->randomElement($users))
                ->setUpdatedAt();

            $imagePath = __DIR__ . '/../../assets/fake_images/travelbook.jpg';
            $newImagePath = $uploadDir . 'travelbook_' . $i . '.jpg';

            $filesystem->copy($imagePath, $newImagePath, true);

            $imageFile = new UploadedFile(
                $newImagePath,
                'travelbook_' . $i . '.jpg',
                'image/jpeg',
                null,
                true
            );

            $travelbook->setImgCouvertureFile($imageFile);
            $travelbook->setImgCouverture('travelbook_' . $i . '.jpg');

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
