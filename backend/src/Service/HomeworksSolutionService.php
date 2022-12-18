<?php

namespace Bot\Service;

use Bot\Attributes\Service;
use Bot\Cache\CacheAdapter;
use Bot\Entity\HomeworkSolution;
use Bot\Repository\HomeworkSolutionRepository;
use Bot\Repository\Impl\HomeworkSolutionRepositoryImpl;

#[Service]
class HomeworksSolutionService
{
    private HomeworkSolutionRepository $repository;
    private CacheAdapter $cacheAdapter;

    public function __construct(CacheAdapter $cacheAdapter)
    {
        $this->repository = new HomeworkSolutionRepositoryImpl();
        $this->cacheAdapter = $cacheAdapter;
    }

    public function getAllSolutions(): array
    {
        return $this->repository->getAllHomeworkSolutions();
    }

    public function getSolution(int $homeworkId, int $userId): ?HomeworkSolution
    {
         $success = $this->cacheAdapter->restore("sol:$homeworkId:$userId");

         if ($success) {
             return HomeworkSolution::fromArray($success);
         }

        return $this->repository->getHomeworkSolutionById($homeworkId, $userId);
    }

    public function saveSolution(int $homeworkId, int $userId, string $text): bool
    {
        $solution = new HomeworkSolution($homeworkId, $userId, $text);

        $this->cacheAdapter->cache("sol:$homeworkId:$userId", $solution->toArray());

        return $this->repository->saveHomeworkSolution($solution);
    }
}
