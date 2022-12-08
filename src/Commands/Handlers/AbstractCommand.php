<?php

namespace Bot\Commands\Handlers;

use Bot\Commands\Command;
use Bot\Commands\CommandsStorage;
use Bot\Entity\Homework;
use Bot\Entity\User;
use Bot\Service\HomeworkService;
use Bot\Service\UserService;
use VK\Client\VKApiClient;

abstract class AbstractCommand implements Command
{
    private VKApiClient $vkApi;
    private CommandsStorage $commandsStorage;
    protected UserService $userService;
    protected HomeworkService $homeworkService;

    public function __construct(VKApiClient $vkApi, CommandsStorage $commandsStorage)
    {
        $this->vkApi = $vkApi;
        $this->commandsStorage = $commandsStorage;
        $this->userService = new UserService();
        $this->homeworkService = new HomeworkService();
    }

    public function getVkApi(): VKApiClient
    {
        return $this->vkApi;
    }

    public function getCommandStorage(): CommandsStorage
    {
        return $this->commandsStorage;
    }


    protected abstract function response(User $user, array $args): ?string;

    protected abstract function register(array $user, array $args): string;

    public function execute(int $user_id, array $args): void
    {
        $users_get_response = $this->vkApi->users()->get(BOT_TOKEN, [
            "user_ids" => [$user_id]
        ]);
        $user = $users_get_response[0];
        $found = $this->userService->get_user_by_id($user_id);

        $message = $found === null ? $this->register($user, $args) : ($this->response($found, $args) ?? $this->register($user, $args));

        $this->vkApi->messages()->send(BOT_TOKEN, [
            "user_id" => $user_id,
            "random_id" => random_int(0, PHP_INT_MAX),
            "message" => $message ?? "Message was `null`",
        ]);
    }
}