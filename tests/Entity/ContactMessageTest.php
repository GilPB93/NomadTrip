<?php

namespace App\Tests\Entity;

use App\Entity\ContactMessage;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class ContactMessageTest extends TestCase
{
    public function testGetAndSetName(): void
    {
        $contactMessage = new ContactMessage();
        $contactMessage->setName('John Doe');

        $this->assertEquals('John Doe', $contactMessage->getName());
    }

    public function testGetAndSetEmail(): void
    {
        $contactMessage = new ContactMessage();
        $contactMessage->setEmail('johndoe@example.com');

        $this->assertEquals('johndoe@example.com', $contactMessage->getEmail());
    }

    public function testGetAndSetSubject(): void
    {
        $contactMessage = new ContactMessage();
        $contactMessage->setSubject('Support Request');

        $this->assertEquals('Support Request', $contactMessage->getSubject());
    }

    public function testGetAndSetMessage(): void
    {
        $contactMessage = new ContactMessage();
        $contactMessage->setMessage('I need help with my account.');

        $this->assertEquals('I need help with my account.', $contactMessage->getMessage());
    }

    public function testGetAndSetSentAt(): void
    {
        $contactMessage = new ContactMessage();
        $sentAt = new DateTimeImmutable();

        $contactMessage->setSentAt($sentAt);

        $this->assertEquals($sentAt, $contactMessage->getSentAt());
    }

    public function testGetAndSetStatus(): void
    {
        $contactMessage = new ContactMessage();
        $contactMessage->setStatus('read');

        $this->assertEquals('read', $contactMessage->getStatus());
    }

    public function testDefaultStatusIsUnread(): void
    {
        $contactMessage = new ContactMessage();
        $this->assertEquals('unread', $contactMessage->getStatus());
    }
}
