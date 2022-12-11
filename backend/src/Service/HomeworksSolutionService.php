<?php

namespace Bot\Service;

use Attributes\Service;
use Bot\Entity\Homework;
use Bot\Database\DatabaseHandler;
use Bot\Entity\HomeworkSolution;
use DateTime;

#[Service]
class HomeworksSolutionService
{
    public function getAllSolutions(): array
    {
        return DatabaseHandler::getAllSolutions();
    }

    public function saveSolution(int $homeworkId, int $userId, string $text): bool
    {
        return DatabaseHandler::saveSolution(new HomeworkSolution($homeworkId, $userId, $text));
    }

    public function getSolution(int $homeworkId, int $userId): ?HomeworkSolution
    {
        return DatabaseHandler::getSolution($homeworkId, $userId);
    }
}
