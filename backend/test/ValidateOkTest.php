<?php

use Bot\Entity\User;
use Bot\Validator\ValidationOk;
use PHPUnit\Framework\TestCase;

class ValidateOkTest extends TestCase
{
    public function testNotOk(): void
    {
        $mock = $this->createMock(ValidationOk::class);

        $mock->assertSame(false, $mock->validateIsStudent(new User(1, 'test', student: false))->isOk());
    }

    public function testIsStudent(): void
    {
        $mock = $this->createMock(ValidationOk::class);

        $mock->assertSame('You must be a student to use this function',
            $mock->validateIsStudent(new User(1, 'test', student: false))->asFailure()?->getComment());
    }

    public function testArguments(): void
    {
        $mock = $this->createMock(ValidationOk::class);

        $mock->assertSame("Invalid number of arguments. Look in `help`\n\t- expected: 1",
            $mock->validateArguments(array('matches' => array(), 'count' => 1))->asFailure()?->getComment());
    }

    public function testValidate(): void
    {
        $mock = $this->createMock(ValidationOk::class);

        $mock->assertSame('You must be a student to use this function',
            $mock->validate('isStudent', new User(1, 'test', student: false))->asFailure()?->getComment());

        $mock->assertSame("Invalid number of arguments. Look in `help`\n\t- expected: 1",
            $mock->validate('arguments', array('matches' => array(), 'count' => 1))->asFailure()?->getComment());
    }
}