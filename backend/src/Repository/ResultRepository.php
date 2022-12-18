<?php

namespace Bot\Repository;

use Bot\Attributes\Repository;

#[Repository]
interface ResultRepository
{
    public function getAllResultByHomework(int $homeworkId): array;

    public function getAllResultByStudent(int $userId): array;

    public function getResultByHwAndStudent(int $homeworkId, int $userId): ?int;

    public function saveResult(int $homeworkId, int $studentId, int $mark): bool;
}