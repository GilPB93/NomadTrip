<?php

namespace App\Tests\Entity;

use App\Entity\Places;
use App\Entity\Travelbook;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class PlacesTest extends TestCase
{
    public function testGetAndSetName(): void
    {
        $place = new Places();
        $place->setName('Louvre Museum');

        $this->assertEquals('Louvre Museum', $place->getName());
    }

    public function testGetAndSetAddress(): void
    {
        $place = new Places();
        $place->setAddress('Rue de Rivoli, 75001 Paris, France');

        $this->assertEquals('Rue de Rivoli, 75001 Paris, France', $place->getAddress());
    }

    public function testGetAndSetVisitAt(): void
    {
        $place = new Places();
        $visitAt = new DateTimeImmutable('2024-06-01');

        $place->setVisitAt($visitAt);

        $this->assertEquals($visitAt, $place->getVisitAt());
    }

    public function testGetAndSetTravelbook(): void
    {
        $place = new Places();
        $travelbook = new Travelbook();

        $place->setTravelbook($travelbook);

        $this->assertSame($travelbook, $place->getTravelbook());
    }
}
