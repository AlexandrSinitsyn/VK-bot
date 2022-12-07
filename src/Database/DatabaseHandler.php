<?php

namespace Bot\Database;

use Bot\Entity\User;
use Bot\Entity\Homework;
use DateTime;

class DatabaseHandler
{
    static function get_user(int $user_id): ?User
    {
        if (!key_exists($user_id, static::get_all_users())) {
            return null;
        }
        return static::get_all_users()[$user_id];
    }

    static function get_hw(int $number): ?Homework
    {
        return static::get_all_hws()[$number];
    }

    static function get_all_users(): array
    {
        return array(1 => new User('alexsin', 1, true));
    }

    static function get_all_hws(): array
    {
        return array(1 => new Homework(1, [], new DateTime()));
    }

    static function save_user(User $user): bool
    {
//        global $users;
        $users = array();

        //    if (key_exists($user->id, $users)) {
        //        return false;
        //    } else {
        $users[$user->id] = $user;
        return true;
        //    }
    }

    static function save_hw(Homework $hw): bool
    {
        global $hws;
        $hws[$hw->number] = $hw;
        return true;
    }
}
