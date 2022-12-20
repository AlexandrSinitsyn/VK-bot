<?php

namespace Bot\Commands\Handlers;

use Bot\Cache\CacheAdapter;
use Bot\Commands\Command;
use Bot\Commands\CommandAdapter;
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
    private CommandAdapter $commandAdapter;
    protected UserService $userService;
    protected HomeworkService $homeworkService;
    protected HomeworksSolutionService $homeworksSolutionService;

    public function __construct(CacheAdapter $cacheAdapter, Validator $validator, CommandAdapter $commandAdapter)
    {
        $this->validator = $validator;
        $this->commandAdapter = $commandAdapter;
        $this->userService = new UserService();
        $this->homeworkService = new HomeworkService();
        $this->homeworksSolutionService = new HomeworksSolutionService($cacheAdapter);
    }

    public function getCommandStorage(): CommandAdapter
    {
        return $this->commandAdapter;
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
    protected abstract function response(User $user, array $args): string;

    /**
     * @throws ValidationException
     */
    protected function register(array $user, array $args): string
    {
        return 'You are not registered';
    }

    public function execute(int $user_id, array $args): string
    {
        $user = ServerHandler::getUsers([$user_id])[0];
        $found = $this->userService->getUserById($user_id);

        $message = $found === null ? $this->register($user, $args) : ($this->response($found, $args) ?? $this->register($user, $args));

        return $message ?? 'Message was `null`';
    }
}