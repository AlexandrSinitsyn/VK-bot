<?php

namespace Bot\Commands;

use Bot\Commands\Handlers\AddHomeworkCommand;
use Bot\Commands\Handlers\GetHomeworkCommand;
use Bot\Commands\Handlers\HelloCommand;
use Bot\Commands\Handlers\HelpCommand;
use Bot\Commands\Handlers\RegistrationCommand;
use Exception;
use VK\Client\VKApiClient;

class CommandsStorage
{
    private array $commands;

    public function __construct(VKApiClient $vkApi)
    {
        $this->commands = array();
        $this->addCommands(
            new AddHomeworkCommand($vkApi, $this),
            new GetHomeworkCommand($vkApi, $this),
            new HelloCommand($vkApi, $this),
            new HelpCommand($vkApi, $this),
            new RegistrationCommand($vkApi, $this),
        );
    }

    public function addCommand(Command $command): void
    {
        $this->commands[trim(strtolower($command->getName()))] = $command;
    }

    public function addCommands(Command ...$commands): void
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
    public function getCommand(string $name): ?Command
    {
        try {
            return $this->commands[trim(strtolower($name))];
        } catch (Exception $e) {
            return null;
        }
    }

    public function executeCommand(string $name, int $user_id, array $args = array()): void
    {
        $this->getCommand($name)->execute($user_id, $args);
    }
}