<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;

#[Validator]
class RegistrationCommandValidator extends AbstractValidator
{
    #[ValidatorCommand]
    public function validateCommand(string $command): ValidationResult
    {
        return ValidationResult::process(preg_match('/^(([Ss]tudent[.!]?)?|[Tt]eacher[.!]?)$/', $command) == 1,
            'Invalid command usage');
    }
}