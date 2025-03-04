<?php

namespace App\Tests\Entity;

use App\Entity\FB;
use App\Entity\Travelbook;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class FBTest extends TestCase
{
    public function testGetAndSetName(): void
    {
        $fb = new FB();
        $fb->setName('Le Meurice');

        $this->assertEquals('Le Meurice', $fb->getName());
    }

    public function testGetAndSetAddress(): void
    {
        $fb = new FB();
        $fb->setAddress('228 Rue de Rivoli, 75001 Paris, France');

        $this->assertEquals('228 Rue de Rivoli, 75001 Paris, France', $fb->getAddress());
    }

    public function testGetAndSetVisitAt(): void
    {
        $fb = new FB();
        $visitAt = new DateTimeImmutable('2024-06-01');

        $fb->setVisitAt($visitAt);

        $this->assertEquals($visitAt, $fb->getVisitAt());
    }

    public function testGetAndSetTravelbook(): void
    {
        $fb = new FB();
        $travelbook = new Travelbook();

        $fb->setTravelbook($travelbook);

        $this->assertSame($travelbook, $fb->getTravelbook());
    }
}
