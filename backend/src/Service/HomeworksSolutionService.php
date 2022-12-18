<?php

namespace Bot\Service;

use Bot\Attributes\Service;
use Bot\Entity\HomeworkSolution;
use Bot\Repository\HomeworkSolutionRepository;
use Bot\Repository\Impl\HomeworkSolutionRepositoryImpl;

#[Service]
class HomeworksSolutionService
{
    private HomeworkSolutionRepository $repository;

    public function __construct()
    {
        $this->repository = new HomeworkSolutionRepositoryImpl();
    }

    public function getAllSolutions(): array
    {
        return $this->repository->getAllHomeworkSolutions();
    }

    public function getSolution(int $homeworkId, int $userId): ?HomeworkSolution
    {
        return $this->repository->getHomeworkSolutionById($homeworkId, $userId);
    }

    public function saveSolution(int $homeworkId, int $userId, string $text): bool
    {
        return $this->repository->saveHomeworkSolution(new HomeworkSolution($homeworkId, $userId, $text));
    }
}
