<?php

namespace Bot\Entity;

class HomeworkSolution
{
    public int $homeworkId;
    public int $userId;
    public string $text;

    public function __construct(int $homeworkId, int $userId, string $text)
    {
        $this->homeworkId = $homeworkId;
        $this->userId = $userId;
        $this->text = $text;
    }


    public function __toString(): string
    {
        return '(' . $this->homeworkId . ')' . "\t" . $this->userId . ':' . "\t" . $this->text . PHP_EOL;
    }
}