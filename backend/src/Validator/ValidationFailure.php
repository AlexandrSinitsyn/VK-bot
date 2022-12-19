<?php

namespace Bot\Validator;

use Bot\Exceptions\ValidationException;
use Exception;

class ValidationFailure extends ValidationResult
{
    private string $comment;
    private ?Exception $exception;

    public function __construct(string $comment, ?Exception $exception = null)
    {
        $this->comment = $comment;
        $this->exception = $exception;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getException(): ?Exception
    {
        return $this->exception;
    }

    public function onThrow(): void
    {
        throw new ValidationException(message: $this->comment, previous: $this->exception);
    }
}
