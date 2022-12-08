<?php

namespace Bot\Entity;

use DateTime;

class Homework
{
    public int $number;
    public array $results;
    public DateTime $deadline;

    public function __construct(int $number, array $results, DateTime $deadline) {
        $this->number = $number;
        $this->results = $results;
        $this->deadline = $deadline;
    }

    public function __toString(): string
    {
        return "#" . $this->number . "\t" . $this->deadline->format("d/m/y") . PHP_EOL;
    }
}