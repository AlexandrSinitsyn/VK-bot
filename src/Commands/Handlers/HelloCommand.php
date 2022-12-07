<?php

namespace Bot\Commands\Handlers;

use Bot\Entity\User;

class HelloCommand extends AbstractCommand
{
    public function getName(): string
    {
        return "Hello";
    }

    public function getDescription(): string
    {
        return "Prints hello message to the chat";
    }
    protected function response(User $user, array $args): string
    {
        return sprintf("Hello, %s!", $user->name);
    }

    protected function register(array $user, array $args): string
    {
        return sprintf("You are unregistered, but still hello, %s! Register yourself, please", $user["first_name"]);
    }
}