<?php

namespace Bot\Commands;

use Exception;

class CommandsStorage
{
    private array $commands;

    public function __construct(Command ...$commands)
    {
        $this->commands = array();
        $this->addCommands(...$commands);
    }

    public function addCommand(Command $command): void
    {
        $this->commands[$command->getName()] = $command;
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
            return $this->commands[$name];
        } catch (Exception $e) {
            return null;
        }
    }

    public function executeCommand(string $name, int $user_id, array $args = array()): void
    {
        $this->getCommand($name)->execute($user_id, $args);
    }
}