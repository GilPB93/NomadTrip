<?php

namespace App\Tests\Entity;

use App\Entity\Places;
use PHPUnit\Framework\TestCase;

class PlacesTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $place = new Places();
        $place->setName('Test Place')
            ->setAddress('Test Address')
            ->setVisitAt(new \DateTimeImmutable())
            ->setTravelbook(null);  // Remplacez par une instance valide de Travelbook si nécessaire

        $this->assertEquals('Test Place', $place->getName());
        $this->assertEquals('Test Address', $place->getAddress());
        $this->assertInstanceOf(\DateTimeImmutable::class, $place->getVisitAt());
        $this->assertNull($place->getTravelbook());
    }

    public function testPlaceEntity(): void
    {
        $place = new Places();
        $place->setName('New Place')
            ->setAddress('456 New Address');

        $this->assertInstanceOf(Places::class, $place);
    }

}