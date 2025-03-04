<?php

namespace App\Tests\Entity;

use App\Entity\Souvenirs;
use App\Entity\Travelbook;
use PHPUnit\Framework\TestCase;

class SouvenirsTest extends TestCase
{
    public function testGetAndSetWhat(): void
    {
        $souvenir = new Souvenirs();
        $souvenir->setWhat('Eiffel Tower Keychain');

        $this->assertEquals('Eiffel Tower Keychain', $souvenir->getWhat());
    }

    public function testGetAndSetForWho(): void
    {
        $souvenir = new Souvenirs();
        $souvenir->setForWho('Mom');

        $this->assertEquals('Mom', $souvenir->getForWho());
    }

    public function testGetAndSetTravelbook(): void
    {
        $souvenir = new Souvenirs();
        $travelbook = new Travelbook();

        $souvenir->setTravelbook($travelbook);

        $this->assertSame($travelbook, $souvenir->getTravelbook());
    }
}
