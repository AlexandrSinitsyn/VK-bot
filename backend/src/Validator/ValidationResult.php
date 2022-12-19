<?php

namespace Bot\Validator;

use Exception;

abstract class ValidationResult
{
    public static function ok(): ValidationOk
    {
        return new ValidationOk();
    }

    public static function failure(string $message, ?Exception $exception = null): ValidationFailure
    {
        return new ValidationFailure($message, $exception);
    }

    public static function process(bool $isValid, $message): ValidationResult
    {
        return $isValid ? self::ok() : self::failure($message);
    }

    public function isOk(): bool
    {
        return $this instanceof ValidationOk;
    }

    public function asFailure(): ?ValidationFailure
    {
        if ($this instanceof ValidationFailure) {
            return $this;
        }

        return null;
    }
}
