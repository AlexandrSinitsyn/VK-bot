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
        return DatabaseHandler::get_all_users();
    }

    function get_user_by_id(int $id): ?User
    {
        return DatabaseHandler::get_user($id);
    }

    function save_user(string $name, int $id, bool $isStudent): bool
    {
        return DatabaseHandler::save_user(new User($name, $id, $isStudent));
    }
}
