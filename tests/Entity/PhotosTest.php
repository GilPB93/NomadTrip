<?php

namespace App\Tests\Entity;

use App\Entity\Photos;
use App\Entity\Travelbook;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;

class PhotosTest extends TestCase
{
    public function testGetAndSetImgUrl(): void
    {
        $photo = new Photos();
        $photo->setImgUrl('photo1.jpg');

        $this->assertEquals('/uploads/photos/photo1.jpg', $photo->getImgUrl());
    }

    public function testGetAndSetAddedAt(): void
    {
        $photo = new Photos();
        $addedAt = new DateTimeImmutable();

        $photo->setAddedAt($addedAt);

        $this->assertEquals($addedAt, $photo->getAddedAt());
    }

    public function testGetAndSetTravelbook(): void
    {
        $photo = new Photos();
        $travelbook = new Travelbook();

        $photo->setTravelbook($travelbook);

        $this->assertSame($travelbook, $photo->getTravelbook());
    }
}
