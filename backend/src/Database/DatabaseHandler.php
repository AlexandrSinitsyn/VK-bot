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
    public static function getUser(int $user_id): ?User
    {
        if (!key_exists($user_id, static::getAllUsers())) {
            return null;
        }
        return static::getAllUsers()[$user_id];
    }

    public static function getHw(int $number): ?Homework
    {
        if (!key_exists($number, static::getAllHws())) {
            return null;
        }
        return static::getAllHws()[$number];
    }

    public static function getAllUsers(): array
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
    public static function getAllHws(): array
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
            $results = explode(',', $row_data[2]);

            $hws[$id] = new Homework($id, $results, $deadline);
        }

        return $hws;
    }

    public static function saveUser(User $user): bool
    {
//        $users = static::get_all_users();
//
//        if (key_exists($user->id, $users)) {
//            return false;
//        } else {
            return file_put_contents(USERS_FILE, $user->id . ' ' . $user->name . ' ' . ($user->student ? '1' : '0') . PHP_EOL, FILE_APPEND | LOCK_EX);
//        }
    }

    public static function saveHw(Homework $hw): bool
    {
//        $hws = static::get_all_hws();
//
//        if (key_exists($hw->number, $hws)) {
//            return false;
//        } else {
            return file_put_contents(HOMEWORKS_FILE, $hw->number . ' ' . $hw->deadline->format('d/m/y') . ' ' . join(',', $hw->results) . PHP_EOL, FILE_APPEND | LOCK_EX);
//        }
    }

    public static function checkHw(int $number, int $studentId, int $mark): bool
    {
        $hw = static::getHw($number);
        $hw->results[] = $mark;
        return self::saveHw($hw);
    }
}