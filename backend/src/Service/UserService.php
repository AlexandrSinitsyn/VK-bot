<?php

namespace Bot\Service;

use Attributes\Service;
use Bot\Entity\User;
use Bot\Database\DatabaseHandler;

#[Service]
class UserService
{
    public function getAllUsers(): array
    {
        return DatabaseHandler::getAllUsers();
    }

    public function getUserById(int $id): ?User
    {
        return DatabaseHandler::getUser($id);
    }

    public function saveUser(string $name, int $id, bool $isStudent): bool
    {
        return DatabaseHandler::saveUser(new User($name, $id, $isStudent));
    }
}
