<?php

namespace Bot\Service;

use Attributes\Service;
use Bot\Entity\User;
use Bot\Database\DatabaseHandler;

#[Service]
class UserService
{
    function getAllUsers(): array
    {
        return DatabaseHandler::getAllUsers();
    }

    function getUserById(int $id): ?User
    {
        return DatabaseHandler::getUser($id);
    }

    function saveUser(string $name, int $id, bool $isStudent): bool
    {
        return DatabaseHandler::saveUser(new User($name, $id, $isStudent));
    }
}
