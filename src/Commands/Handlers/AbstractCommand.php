<?php

namespace Bot\Commands\Handlers;

use Bot\Commands\Command;
use Bot\Entity\User;
use Bot\Service\UserService;
use VK\Client\VKApiClient;

abstract class AbstractCommand implements Command
{
    protected VKApiClient $vkApi;
    protected UserService $userService;

    public function __construct(VKApiClient $vkApi)
    {
        $this->vkApi = $vkApi;
        $this->userService = new UserService();
    }

    protected abstract function response(User $user, array $args): string;

    protected abstract function register(array $user, array $args): string;

    public function execute(int $user_id, array $args): void
    {
        $users_get_response = $this->vkApi->users()->get(BOT_TOKEN, [
            "user_ids" => [$user_id]
        ]);
        $user = $users_get_response[0];
        $found = $this->userService->get_user_by_id($user_id);

        $this->vkApi->messages()->send(BOT_TOKEN, [
            "user_id" => $user_id,
            "random_id" => random_int(0, PHP_INT_MAX),
            "message" => $found === null ? $this->register($user, $args) : $this->response($found, $args),
        ]);
    }
}