<?php

namespace Bot\Validator;

interface Validator
{
    public function validate(string $methodName, mixed $value): ValidationResult;

    public function validateOk(): ValidationResult;
}