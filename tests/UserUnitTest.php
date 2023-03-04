<?php

namespace App\Tests;

use App\Entity\Ticket;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testSetAndGetUserData(): void
    {
        $user = new User();
        $user->setEmail('admin@esp.sn');
        $user->setFirstName('Admin');
        $user->setLastName('Admin');
        $user->setRoles(['ROLE_ADMIN']);
        // hash the password using the UserPasswordHasherInterface
        $user->setPassword("passer");

        self::assertSame('admin@esp.sn', $user->getEmail());
        self::assertSame('Admin', $user->getFirstName());
        self::assertSame('Admin', $user->getLastName());
        self::assertSame(['ROLE_ADMIN'], $user->getRoles());
        self::assertSame('passer', $user->getPassword());
    }

    public function testTicketSetAndGetData(): void
    {
        $user = new User();
        $user->setEmail('admin@esp.sn');
        $user->setFirstName('Admin');
        $user->setLastName('Admin');
        $user->setRoles(['ROLE_ADMIN']);
        // hash the password using the UserPasswordHasherInterface
        $user->setPassword("passer");

        $ticket = new Ticket();
        $ticket->setNumber('TICKET-001');
        $ticket->setStatus('EN COURS');
        $ticket->setCreatedAt(new \DateTimeImmutable());
        $ticket->setCreatedBy($user);

        self::assertSame('TICKET-001', $ticket->getNumber());
        self::assertSame('EN COURS', $ticket->getStatus());
        self::assertInstanceOf(\DateTimeImmutable::class, $ticket->getCreatedAt());
        self::assertInstanceOf(User::class, $ticket->getCreatedBy());

    }

}
