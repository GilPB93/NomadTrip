<?php

namespace App\Tests\Entity;

use App\Entity\Travelbook;
use App\Entity\User;
use App\Entity\Places;
use App\Entity\FB;
use App\Entity\Souvenirs;
use App\Entity\Photos;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;

class TravelbookTest extends TestCase
{
    public function testGetAndSetTitle(): void
    {
        $travelbook = new Travelbook();
        $travelbook->setTitle('My Travelbook');
        $this->assertEquals('My Travelbook', $travelbook->getTitle());
    }

    public function testGetAndSetImgCouverture(): void
    {
        $travelbook = new Travelbook();
        $travelbook->setImgCouverture('cover.jpg');
        $this->assertEquals('cover.jpg', $travelbook->getImgCouverture());
    }

    public function testGetImgCouvertureUrl(): void
    {
        $travelbook = new Travelbook();
        $travelbook->setImgCouverture('cover.jpg');

        $this->assertEquals('/uploads/images/travelbooks/cover.jpg', $travelbook->getImgCouvertureUrl());
    }

    public function testGetAndSetDepartureAt(): void
    {
        $travelbook = new Travelbook();
        $departureDate = new DateTimeImmutable('2024-06-01');
        $travelbook->setDepartureAt($departureDate);

        $this->assertEquals($departureDate, $travelbook->getDepartureAt());
    }

    public function testGetAndSetComebackAt(): void
    {
        $travelbook = new Travelbook();
        $departureDate = new DateTimeImmutable('2024-06-01');
        $comebackDate = new DateTimeImmutable('2024-06-10');

        $travelbook->setDepartureAt($departureDate);
        $travelbook->setComebackAt($comebackDate);

        $this->assertEquals($comebackDate, $travelbook->getComebackAt());
    }

    public function testSetComebackAtThrowsExceptionIfBeforeDeparture(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The comeback date must be after the departure date.');

        $travelbook = new Travelbook();
        $departureDate = new DateTimeImmutable('2024-06-10');
        $comebackDate = new DateTimeImmutable('2024-06-01');

        $travelbook->setDepartureAt($departureDate);
        $travelbook->setComebackAt($comebackDate);
    }

    public function testGetAndSetFlightNumber(): void
    {
        $travelbook = new Travelbook();
        $travelbook->setFlightNumber('FL12345');

        $this->assertEquals('FL12345', $travelbook->getFlightNumber());
    }

    public function testGetAndSetAccommodation(): void
    {
        $travelbook = new Travelbook();
        $travelbook->setAccommodation('Hilton Hotel');

        $this->assertEquals('Hilton Hotel', $travelbook->getAccommodation());
    }

    public function testGetAndSetCreatedAt(): void
    {
        $travelbook = new Travelbook();
        $createdAt = new DateTimeImmutable();
        $travelbook->setCreatedAt($createdAt);

        $this->assertEquals($createdAt, $travelbook->getCreatedAt());
    }

    public function testGetAndSetUpdatedAt(): void
    {
        $travelbook = new Travelbook();
        $updatedAt = new DateTimeImmutable();
        $travelbook->setUpdatedAt($updatedAt);

        $this->assertEquals($updatedAt, $travelbook->getUpdatedAt());
    }

    public function testGetAndSetUser(): void
    {
        $travelbook = new Travelbook();
        $user = new User();
        $travelbook->setUser($user);

        $this->assertSame($user, $travelbook->getUser());
    }

    public function testAddAndRemovePlace(): void
    {
        $travelbook = new Travelbook();
        $place = new Places();

        $travelbook->addPlace($place);
        $this->assertTrue($travelbook->getPlaces()->contains($place));

        $travelbook->removePlace($place);
        $this->assertFalse($travelbook->getPlaces()->contains($place));
    }

    public function testAddAndRemoveFB(): void
    {
        $travelbook = new Travelbook();
        $fb = new FB();

        $travelbook->addFB($fb);
        $this->assertTrue($travelbook->getFBs()->contains($fb));

        $travelbook->removeFB($fb);
        $this->assertFalse($travelbook->getFBs()->contains($fb));
    }

    public function testAddAndRemoveSouvenir(): void
    {
        $travelbook = new Travelbook();
        $souvenir = new Souvenirs();

        $travelbook->addSouvenir($souvenir);
        $this->assertTrue($travelbook->getSouvenirs()->contains($souvenir));

        $travelbook->removeSouvenir($souvenir);
        $this->assertFalse($travelbook->getSouvenirs()->contains($souvenir));
    }

    public function testAddAndRemovePhoto(): void
    {
        $travelbook = new Travelbook();
        $photo = new Photos();

        $travelbook->addPhoto($photo);
        $this->assertTrue($travelbook->getPhotos()->contains($photo));

        $travelbook->removePhoto($photo);
        $this->assertFalse($travelbook->getPhotos()->contains($photo));
    }
}
