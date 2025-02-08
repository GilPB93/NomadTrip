<?php

namespace App\Tests\Entity;

use App\Entity\Travelbook;
use App\Entity\User;
use App\Enum\AccountStatus;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetSetEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $this->assertSame('test@example.com', $user->getEmail());
    }

    public function testGetSetFirstName(): void
    {
        $user = new User();
        $user->setFirstName('John');
        $this->assertSame('John', $user->getFirstName());
    }

    public function testGetSetAccountStatus(): void
    {
        $user = new User();
        $status = AccountStatus::ACTIVE;
        $user->setAccountStatus($status);
        $this->assertSame($status, $user->getAccountStatus());
    }

    public function testAddRemoveTravelbook(): void
    {
        $user = new User();
        $travelbook = $this->createMock(Travelbook::class);

        $user->addTravelbook($travelbook);
        $this->assertCount(1, $user->getTravelbooks());

        $user->removeTravelbook($travelbook);
        $this->assertCount(0, $user->getTravelbooks());
    }

    public function testSetUpdatedAt(): void
    {
        $user = new User();
        $updatedAt = new \DateTimeImmutable();
        $user->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }

    public function testGetSetLastLogin(): void
    {
        $user = new User();
        $now = new \DateTimeImmutable();

        $user->setLastLogin($now);
        $this->assertSame($now, $user->getLastLogin());
    }

    public function testGetSetConnectionTime(): void
    {
        $user = new User();

        $user->addConnectionTime(3600);
        $this->assertEquals(3600, $user->getConnectionTime());

        $user->addConnectionTime(1200);
        $this->assertEquals(4800, $user->getConnectionTime());
    }
}