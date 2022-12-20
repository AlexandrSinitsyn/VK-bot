<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Entity\User;
use Bot\Validator\ValidationResult;
use Bot\Validator\Validator;

abstract class AbstractValidator implements Validator
{
    public function validate(string $methodName, mixed $value): ValidationResult
    {
        $fun = 'validate' . ucfirst($methodName);
        return method_exists($this, $fun) ? $this->$fun($value) : $this->validateOk();
    }

    public function validateOk(): ValidationResult
    {
        return ValidationResult::ok();
    }

    #[ValidatorCommand]
    public function validateIsStudent(User $user): ValidationResult
    {
        return ValidationResult::process($user->student === true,
            'You must be a student to use this function');
    }

    #[ValidatorCommand]
    public function validateIsTeacher(User $user): ValidationResult
    {
        return ValidationResult::process($user->student === false,
            'You must be a teacher to use this function');
    }

    #[ValidatorCommand]
    public function validateArguments(array $matches): ValidationResult
    {
        $actual = count($matches['matches']);
        $expected = (int) $matches['count'];
        return ValidationResult::process($actual === $expected,
            "Invalid number of arguments. Look in `help`\n\t- expected: $expected"); // \n\t- actual: $actual
    }
}
