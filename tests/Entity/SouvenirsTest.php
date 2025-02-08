<?php

namespace App\Tests\Entity;

use App\Entity\Souvenirs;
use PHPUnit\Framework\TestCase;

class SouvenirsTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $souvenir = new Souvenirs();
        $souvenir->setWhat('Hat')
            ->setForWho('John Doe');

        $this->assertEquals('Hat', $souvenir->getWhat());
        $this->assertEquals('John Doe', $souvenir->getForWho());
    }

    public function testSetTravelbook()
    {
        $souvenir = new Souvenirs();
        $souvenir->setTravelbook(null);  // Assuming you may have a Travelbook object

        $this->assertNull($souvenir->getTravelbook());
    }
}