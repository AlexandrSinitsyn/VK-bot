<?php

namespace Bot\Repository;

use Bot\Attributes\Repository;
use Bot\Entity\Homework;

#[Repository]
interface HomeworkRepository
{
    public function getAllHomeworks(): array;
    public function getHomeworkById(int $number): ?Homework;
    public function saveHomework(Homework $hw): bool;
}