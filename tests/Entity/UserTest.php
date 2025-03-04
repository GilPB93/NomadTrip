<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Enum\AccountStatus;
use Exception;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetAndSetEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testGetAndSetRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testGetAndSetPassword(): void
    {
        $user = new User();
        $user->setPassword('hashedpassword');
        $this->assertEquals('hashedpassword', $user->getPassword());
    }

    /**
     * @throws Exception
     */
    public function testGetAndSetFirstName(): void
    {
        $user = new User();
        $user->setFirstName('John');
        $this->assertEquals('John', $user->getFirstName());
    }

    public function testGetAndSetLastName(): void
    {
        $user = new User();
        $user->setLastName('Doe');
        $this->assertEquals('Doe', $user->getLastName());
    }

    public function testGetAndSetPseudo(): void
    {
        $user = new User();
        $user->setPseudo('john_doe');
        $this->assertEquals('john_doe', $user->getPseudo());
    }

    public function testGetAndSetAccountStatus(): void
    {
        $user = new User();
        $status = AccountStatus::ACTIVE;
        $user->setAccountStatus($status);
        $this->assertEquals($status, $user->getAccountStatus());
    }

    public function testGeneratedApiTokenIsValid(): void
    {
        $user = new User();
        $this->assertNotNull($user->getApiToken());
        $this->assertEquals(64, strlen($user->getApiToken())); // 32 bytes in hex
    }

    public function testEraseCredentialsDoesNotThrowError(): void
    {
        $user = new User();
        $this->assertNull($user->eraseCredentials());
    }
}
