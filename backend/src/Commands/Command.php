<?php

namespace Bot\Commands;

use Bot\Exceptions\ValidationException;
use Bot\Validator\ValidationResult;
use VK\Client\VKApiClient;

interface Command
{
    public function getVkApi(): VKApiClient;

    public function validate(string $methodName, mixed $value): ValidationResult;

    public function getCommandStorage(): CommandsStorage;

    public function getName(): string;

    public function getDescription(): string;

    /**
     * @throws ValidationException
     */
    public function execute(int $user_id, array $args): void;
}