<?php

namespace Bot\Validator\ValidatorImpl;

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
}