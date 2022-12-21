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

    /**
     * @throws ValidationException
     */
    protected function run(array|User $user, array $args): string
    {
        return $user instanceof User ? $this->response($user, $args) : $this->register($user, $args);
    }

    public function execute(int $user_id, array $args): string
    {
        $user = ServerHandler::getUsers([$user_id])[0];
        $found = $this->userService->getUserById($user_id);

        return $this->run($found ?? $user, $args);
    }
}