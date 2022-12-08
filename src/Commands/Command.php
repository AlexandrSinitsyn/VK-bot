<?php

namespace Bot\Commands;

use VK\Client\VKApiClient;

interface Command
{
    public function getVkApi(): VKApiClient;

    public function getCommandStorage(): CommandsStorage;

    public function getName(): string;

    public function getDescription(): string;

    public function execute(int $user_id, array $args): void;
}