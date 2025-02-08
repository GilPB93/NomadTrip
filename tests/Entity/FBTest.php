<?php

namespace App\Tests\Entity;

use App\Entity\FB;
use App\Entity\Travelbook;
use PHPUnit\Framework\TestCase;

class FBTest extends TestCase
{
    public function testFBSettersAndGetters(): void
    {
        $travelbook = new Travelbook();
        $travelbook->setTitle("Travelbook 1");

        $fb = new FB();
        $fb->setName("Restaurant 1")
            ->setAddress("123 Street")
            ->setVisitAt(new \DateTimeImmutable())
            ->setTravelbook($travelbook);

        $this->assertEquals("Restaurant 1", $fb->getName());
        $this->assertEquals("123 Street", $fb->getAddress());
        $this->assertInstanceOf(\DateTimeImmutable::class, $fb->getVisitAt());
        $this->assertEquals($travelbook, $fb->getTravelbook());
    }

}