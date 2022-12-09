<?php

namespace Bot\Service;

use Attributes\Service;
use Bot\Entity\Homework;
use Bot\Database\DatabaseHandler;
use DateTime;

#[Service]
class HomeworkService
{
    function getAllHomeworks(): array
    {
        return DatabaseHandler::getAllHws();
    }

    function getHomeworkById(int $id): ?Homework
    {
        return DatabaseHandler::getHw($id);
    }

    function saveHomework(int $id, array $res, DateTime $deadline): bool
    {
        return DatabaseHandler::saveHw(new Homework($id, $res, $deadline));
    }
}
