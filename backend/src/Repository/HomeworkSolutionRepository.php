<?php

namespace Bot\Repository;

use Bot\Attributes\Repository;
use Bot\Entity\HomeworkSolution;

#[Repository]
interface HomeworkSolutionRepository
{
    public function getAllHomeworkSolutions(): array;
    public function getHomeworkSolutionById(int $hwId, int $userId): ?HomeworkSolution;
    public function saveHomeworkSolution(HomeworkSolution $solution): bool;
}