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

    public function toArray(): array
    {
        return array(
            'hwid' => $this->homeworkId,
            'uid' => $this->userId,
            'text' => $this->text
        );
    }

    public static function fromArray(array $arr): HomeworkSolution
    {
        return new HomeworkSolution((int) $arr['hwid'], (int) $arr['uid'], $arr['text']);
    }

    public function __toString(): string
    {
        return '(' . $this->homeworkId . ')' . "\t" . $this->userId . ':' . "\t" . $this->text . PHP_EOL;
    }
}