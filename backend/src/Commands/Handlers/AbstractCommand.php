<?php

namespace Bot\Commands\Handlers;

use Bot\Cache\CacheAdapter;
use Bot\Commands\Command;
use Bot\Commands\CommandsStorage;
use Bot\Entity\User;
use Bot\Exceptions\ValidationException;
use Bot\ServerHandler;
use Bot\Service\HomeworkService;
use Bot\Service\HomeworksSolutionService;
use Bot\Service\UserService;
use Bot\Validator\ValidationResult;
use Bot\Validator\ValidationTransaction;
use Bot\Validator\Validator;

abstract class AbstractCommand implements Command
{
    private Validator $validator;
    private CommandsStorage $commandsStorage;
    protected UserService $userService;
    protected HomeworkService $homeworkService;
    protected HomeworksSolutionService $homeworksSolutionService;

    public function __construct(CacheAdapter $cacheAdapter, Validator $validator, CommandsStorage $commandsStorage)
    {
        $this->validator = $validator;
        $this->commandsStorage = $commandsStorage;
        $this->userService = new UserService();
        $this->homeworkService = new HomeworkService();
        $this->homeworksSolutionService = new HomeworksSolutionService($cacheAdapter);
    }

    public function getCommandStorage(): CommandsStorage
    {
        return $this->commandsStorage;
    }

    public function validate(string $methodName, mixed $value): ValidationResult
    {
        return $this->validator->validate($methodName, $value);
    }

    public function transaction(): ValidationTransaction
    {
        return new ValidationTransaction();
    }

    /**
     * @throws ValidationException
     */
    protected abstract function response(User $user, array $args): ?string;

    /**
     * @throws ValidationException
     */
    protected abstract function register(array $user, array $args): string;

    public function execute(int $user_id, array $args): void
    {
        $user = ServerHandler::getUsers([$user_id])[0];
        $found = $this->userService->getUserById($user_id);

        $message = $found === null ? $this->register($user, $args) : ($this->response($found, $args) ?? $this->register($user, $args));

        ServerHandler::sendMessage($user_id, $message ?? 'Message was `null`');
    }
}