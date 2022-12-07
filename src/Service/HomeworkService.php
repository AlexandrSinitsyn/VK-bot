<?php

namespace Bot\Service;

use Bot\Entity\Homework;
use Bot\Database\DatabaseHandler;
use DateTime;

class HomeworkService
{
    function getAllHomeworks(): array
    {
        return DatabaseHandler::get_all_hws();
    }

    function get_homework_by_id(int $id): ?Homework
    {
        return DatabaseHandler::get_hw($id);
    }

    function save_homework(int $id, array $res, DateTime $deadline): bool
    {
        return DatabaseHandler::save_hw(new Homework($id, $res, $deadline));
    }
}
