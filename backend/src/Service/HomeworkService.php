<?php

namespace Bot\Service;

use Bot\Attributes\Service;
use Bot\Entity\Homework;
use Bot\Repository\HomeworkRepository;
use Bot\Repository\Impl\HomeworkRepositoryImpl;
use Bot\Repository\Impl\ResultRepositoryImpl;
use Bot\Repository\ResultRepository;
use DateTime;

#[Service]
class HomeworkService
{
    private HomeworkRepository $repository;
    private ResultRepository $resultRepository;

    public function __construct()
    {
        $this->repository = new HomeworkRepositoryImpl();
        $this->resultRepository = new ResultRepositoryImpl();
    }

    public function getAllHomeworks(): array
    {
        return $this->repository->getAllHomeworks();
    }

    public function getHomeworkById(int $id): ?Homework
    {
        $hw = $this->repository->getHomeworkById($id);
        $hw->results = $this->resultRepository->getAllResultByHomework($id);
        return $hw;
    }

    public function saveHomework(int $id, array $res, DateTime $deadline): bool
    {
        return $this->repository->saveHomework(new Homework($id, $res, $deadline));
    }

    public function checkHomework(int $number, int $studentId, int $mark): bool
    {
        return $this->resultRepository->saveResult($number, $studentId, $mark);
    }
}
