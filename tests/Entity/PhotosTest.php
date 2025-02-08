<?php

namespace App\Tests\Entity;

use App\Entity\Photos;
use App\Entity\Travelbook;
use PHPUnit\Framework\TestCase;

class PhotosTest extends TestCase
{
    public function testSetGetImgUrl(): void
    {
        $photo = new Photos();
        $photo->setImgUrl('https://example.com/photo.jpg');

        $this->assertEquals('https://example.com/photo.jpg', $photo->getImgUrl());
    }

    public function testSetGetAddedAt(): void
    {
        $photo = new Photos();
        $date = new \DateTimeImmutable('2025-01-29');
        $photo->setAddedAt($date);

        $this->assertEquals($date, $photo->getAddedAt());
    }

    public function testSetGetTravelbook(): void
    {
        $photo = new Photos();
        $travelbook = new Travelbook();
        $photo->setTravelbook($travelbook);

        $this->assertSame($travelbook, $photo->getTravelbook());
    }
}