<?php

use Bot\Commands\Handlers\HelloCommand;
use Bot\Commands\Handlers\RegistrationCommand;
use Bot\Entity\User;
use Bot\Service\UserService;
use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    public function testHelloUnregistered(): void
    {
        $mock = $this->createMock(HelloCommand::class);

        $mock->assertSame('You are unregistered, but still hello, Test! Register yourself, please', $mock->run(array('first_name' => 'Test')));
    }

    public function testHelloRegistered(): void
    {
        $mock = $this->createMock(HelloCommand::class);

        $mock->assertSame('Hello, Test!', $mock->run(new User('Test', 1, true)));
    }

    public function testUserRegistrationOk(): void
    {
        $mock = $this->createMock(RegistrationCommand::class);

        $userService = $this->createMock(UserService::class);
        $userService->method('saveUser')->willReturn(true);

        $mock->set('userService', $userService);

        $mock->assertSame('Ok', $mock->run(new User('Test', 1, true)));
    }

    public function testDoubleUserRegistrationFail(): void
    {
        $mock = $this->createMock(RegistrationCommand::class);

        $userService = $this->createMock(UserService::class);
        $userService->method('saveUser')->willReturn(true);

        $mock->set('userService', $userService);

        $mock->assertSame('You can not re-register', $mock->run(new User('Test', 1, true)));
    }
}