<?php

namespace Bot\Service;

use Bot\Entity\User;
use Bot\Database\DatabaseHandler;

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

    function save_user(User $user): bool
    {
        return DatabaseHandler::save_user($user);
    }
}
