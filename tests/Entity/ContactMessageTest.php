<?php

namespace App\Tests\Entity;

use App\Entity\ContactMessage;
use PHPUnit\Framework\TestCase;

class ContactMessageTest extends TestCase
{
    public function testCreateContactMessage(): void
    {
        $message = new ContactMessage();
        $message->setName("Test Name")
            ->setEmail("test@example.com")
            ->setSubject("Test Subject")
            ->setMessage("Test Message")
            ->setSentAt(new \DateTimeImmutable());

        $this->assertEquals("Test Name", $message->getName());
        $this->assertEquals("test@example.com", $message->getEmail());
        $this->assertEquals("Test Subject", $message->getSubject());
        $this->assertEquals("Test Message", $message->getMessage());
        $this->assertInstanceOf(\DateTimeImmutable::class, $message->getSentAt());
    }
}