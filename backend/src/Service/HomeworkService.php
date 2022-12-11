<?php

namespace Bot\Service;

use Attributes\Service;
use Bot\Entity\Homework;
use Bot\Database\DatabaseHandler;
use DateTime;

#[Service]
class HomeworkService
{
    public function getAllHomeworks(): array
    {
        return DatabaseHandler::getAllHws();
    }

    public function getHomeworkById(int $id): ?Homework
    {
        return DatabaseHandler::getHw($id);
    }

    public function saveHomework(int $id, array $res, DateTime $deadline): bool
    {
        return DatabaseHandler::saveHw(new Homework($id, $res, $deadline));
    }

    public function checkHomework(int $number, int $studentId, int $mark): bool
    {
        return DatabaseHandler::checkHw($number, $studentId, $mark);
    }
}
