<?php

use Bot\Entity\Homework;
use Bot\Entity\User;
use Bot\Service\HomeworkService;
use Bot\Validator\ValidatorImpl\AddHomeworkCommandValidator;
use PHPUnit\Framework\TestCase;

class ValidateAddHomeworkTest extends TestCase
{
    public function testIsTeacher(): void
    {
        $mock = $this->createMock(AddHomeworkCommandValidator::class);

        $mock->assertSame('You must be a teacher to use this function',
            $mock->validateIsStudent(new User(1, 'test', student: true))->asFailure()?->getComment());
    }

    public function testArguments(): void
    {
        $mock = $this->createMock(AddHomeworkCommandValidator::class);

        $mock->assertSame("Invalid number of arguments. Look in `help`\n\t- expected: 1",
            $mock->validateArguments(array('matches' => array(), 'count' => 1))->asFailure()?->getComment());
    }

    public function testValidate(): void
    {
        $mock = $this->createMock(AddHomeworkCommandValidator::class);

        $mock->assertSame('You must be a student to use this function',
            $mock->validate('isStudent', new User(1, 'test', student: false))->asFailure()?->getComment());

        $mock->assertSame("Invalid number of arguments. Look in `help`\n\t- expected: 1",
            $mock->validate('arguments', array('matches' => array(), 'count' => 1))->asFailure()?->getComment());
    }

    public function testValidateDate(): void
    {
        $mock = $this->createMock(AddHomeworkCommandValidator::class);

        $mock->assertSame('Invalid date',
            $mock->validate('date', 'invalid')->asFailure()?->getComment());

        $mock->assertSame('Deadline has already expired',
            $mock->validate('date', '11-11-1111')->asFailure()?->getComment());
    }

    public function testValidateUnique(): void
    {
        $mock = $this->createMock(AddHomeworkCommandValidator::class);

        $service = $this->createMock(HomeworkService::class);
        $service->method('getHomeworkById')->willReturn(null);

        $mock->assertSame(true, $mock->validate('unique', 1)->isOk());

        $service->method('getHomeworkById')->willReturn(new Homework(1, array(), new DateTime()));

        $mock->assertSame('Homework is not unique',
            $mock->validate('unique', 1)->asFailure()?->getComment());
    }
}