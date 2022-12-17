<?php

namespace Bot\Database;

use Bot\Entity\Homework;
use Bot\Entity\User;

class DbParser
{
    public static function parseUsers(array $arr): array
    {
        $users = array();
        foreach($arr as $row => $data) {
            $row_data = explode(' ', $data);

            $id = (int) $row_data[0];
            $name = $row_data[1];
            $isStudent = (bool) $row_data[2];

            $users[$id] = new User($name, $id, $isStudent);
        }

        return $users;
    }

    public static function parseHomeworks(array $arr): array
    {
        $hws = array();
        foreach($arr as $row => $data) {
            $row_data = explode(' ', $data);

            $id = (int) $row_data[0];
            $deadline = new DateTime($row_data[1]);
            $results = explode(',', $row_data[2]);

            $hws[$id] = new Homework($id, $results, $deadline);
        }

        return $hws;
    }
}