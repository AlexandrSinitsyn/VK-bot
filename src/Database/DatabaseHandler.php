<?php

namespace Bot\Database;

use Bot\Entity\User;
use Bot\Entity\Homework;
use DateTime;
use Exception;

const USERS_FILE = './users.tmp';
const HOMEWORKS_FILE = './homeworks.tmp';

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
//        return array(1 => new User('alexsin', 1, true));
        $txt_file = file_get_contents(USERS_FILE);
        $rows = explode("\n", $txt_file);
        array_shift($rows);
        array_pop($rows);

        $users = array();
        foreach($rows as $row => $data) {
            $row_data = explode(' ', $data);

            $id = (int) $row_data[0];
            $name = $row_data[1];
            $isStudent = (bool) $row_data[2];

            $users[$id] = new User($name, $id, $isStudent);
        }

        return $users;
    }

    /**
     * @throws Exception
     */
    static function get_all_hws(): array
    {
//        return array(1 => new Homework(1, [], new DateTime()));
        $txt_file = file_get_contents(HOMEWORKS_FILE);
        $rows = explode("\n", $txt_file);
        array_shift($rows);
        array_pop($rows);

        $hws = array();
        foreach($rows as $row => $data) {
            $row_data = explode(' ', $data);

            $id = (int) $row_data[0];
            $deadline = new DateTime($row_data[1]);
            $results = preg_split(',', $row_data[2]);

            $hws[$id] = new Homework($id, $results, $deadline);
        }

        return $hws;
    }

    static function save_user(User $user): bool
    {
//        $users = static::get_all_users();
//
//        if (key_exists($user->id, $users)) {
//            return false;
//        } else {
            return file_put_contents(USERS_FILE, $user->id . " " . $user->name . " " . $user->student . PHP_EOL, FILE_APPEND | LOCK_EX);
//        }
    }

    static function save_hw(Homework $hw): bool
    {
//        $hws = static::get_all_hws();
//
//        if (key_exists($hw->number, $hws)) {
//            return false;
//        } else {
            return file_put_contents(HOMEWORKS_FILE, $hw->number . " " . $hw->deadline->format('d-m-y') . " " . join(',', $hw->results) . PHP_EOL, FILE_APPEND | LOCK_EX);
//        }
    }
}
