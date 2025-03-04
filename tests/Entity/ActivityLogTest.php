<?php

namespace App\Tests\Entity;

use App\Entity\ActivityLog;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;
use DateTime;

class ActivityLogTest extends TestCase
{
    public function testGetAndSetLogin(): void
    {
        $activityLog = new ActivityLog();
        $loginTime = new DateTime();

        $activityLog->setLogin($loginTime);

        $this->assertEquals($loginTime, $activityLog->getLogin());
    }

    public function testGetAndSetLogout(): void
    {
        $activityLog = new ActivityLog();
        $logoutTime = new DateTime();

        $activityLog->setLogout($logoutTime);

        $this->assertEquals($logoutTime, $activityLog->getLogout());
    }

    public function testGetAndSetDurationOfConnection(): void
    {
        $activityLog = new ActivityLog();
        $activityLog->setDurationOfConnection(3600);

        $this->assertEquals(3600, $activityLog->getDurationOfConnection());
    }

    public function testGetAndSetUser(): void
    {
        $activityLog = new ActivityLog();
        $user = new User();

        $activityLog->setUser($user);

        $this->assertSame($user, $activityLog->getUser());
    }
}
