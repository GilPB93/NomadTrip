<?php

namespace App\Tests\Entity;

use App\Entity\Travelbook;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TravelbookTest extends TestCase
{
    public function testSetAndGetTitle()
    {
        $travelbook = new Travelbook();
        $travelbook->setTitle('Test Trip');
        $this->assertEquals('Test Trip', $travelbook->getTitle());
    }

    public function testSetAndGetDepartureAt()
    {
        $travelbook = new Travelbook();
        $date = new DateTimeImmutable('2025-06-01');
        $travelbook->setDepartureAt($date);
        $this->assertEquals($date, $travelbook->getDepartureAt());
    }

    public function testSetAndGetComebackAt()
    {
        $travelbook = new Travelbook();
        $date = new DateTimeImmutable('2025-06-15');
        $travelbook->setComebackAt($date);
        $this->assertEquals($date, $travelbook->getComebackAt());
    }

    public function testSetAndGetFlightNumber()
    {
        $travelbook = new Travelbook();
        $travelbook->setFlightNumber('AF123');
        $this->assertEquals('AF123', $travelbook->getFlightNumber());
    }

    public function testSetAndGetAccommodation()
    {
        $travelbook = new Travelbook();
        $travelbook->setAccommodation('Hotel Paris');
        $this->assertEquals('Hotel Paris', $travelbook->getAccommodation());
    }
}