<?php

namespace Bot\Database;

use Bot\Entity\Homework;
use Bot\Entity\HomeworkSolution;
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

    public static function parseHomeworkSolutions(array $arr): array
    {
        $solutions = array();
        foreach($arr as $row => $data) {
            $row_data = explode(' ', $data, 3);

            $homeworkId = (int) $row_data[0];
            $userId = (int) $row_data[1];
            $text = $row_data[2];

            $solutions[] = new HomeworkSolution($homeworkId, $userId, $text);
        }

        return $solutions;
    }
}