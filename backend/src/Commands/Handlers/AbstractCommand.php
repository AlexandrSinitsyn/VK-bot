<?php

namespace Bot\Commands\Handlers;

use Bot\Cache\CacheAdapter;
use Bot\Commands\Command;
use Bot\Commands\CommandsStorage;
use Bot\Entity\User;
use Bot\Exceptions\ValidationException;
use Bot\Service\HomeworkService;
use Bot\Service\HomeworksSolutionService;
use Bot\Service\UserService;
use Bot\Validator\ValidationResult;
use Bot\Validator\Validator;
use VK\Client\VKApiClient;

abstract class AbstractCommand implements Command
{
    private VKApiClient $vkApi;
    private Validator $validator;
    private CommandsStorage $commandsStorage;
    protected UserService $userService;
    protected HomeworkService $homeworkService;
    protected HomeworksSolutionService $homeworksSolutionService;

    public function __construct(VKApiClient $vkApi, CacheAdapter $cacheAdapter, Validator $validator, CommandsStorage $commandsStorage)
    {
        $this->vkApi = $vkApi;
        $this->validator = $validator;
        $this->commandsStorage = $commandsStorage;
        $this->userService = new UserService();
        $this->homeworkService = new HomeworkService();
        $this->homeworksSolutionService = new HomeworksSolutionService($cacheAdapter);
    }

    public function getVkApi(): VKApiClient
    {
        return $this->vkApi;
    }

    public function getCommandStorage(): CommandsStorage
    {
        return $this->commandsStorage;
    }

    public function validate(string $methodName, mixed $value): ValidationResult
    {
        return $this->validator->validate($methodName, $value);
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
        $users_get_response = $this->vkApi->users()->get(BOT_TOKEN, [
            'user_ids' => [$user_id]
        ]);
        $user = $users_get_response[0];
        $found = $this->userService->getUserById($user_id);

        $message = $found === null ? $this->register($user, $args) : ($this->response($found, $args) ?? $this->register($user, $args));

        $this->vkApi->messages()->send(BOT_TOKEN, [
            'user_id' => $user_id,
            'random_id' => random_int(0, PHP_INT_MAX),
            'message' => $message ?? 'Message was `null`',
        ]);
    }
}