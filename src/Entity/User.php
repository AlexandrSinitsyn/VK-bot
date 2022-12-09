<?php

namespace Bot\Entity;

class User {
    public string $name;
    public int $id;
    public bool $student;

    public function __construct(string $name, int $id, bool $student = true) {
        $this->name = $name;
        $this->id = $id;
        $this->student = $student;
    }

    public function __toString(): string
    {
        return '#' . $this->id . "\t" . $this->name . '[' . ($this->student ? 'student' : 'teacher') . ']' . PHP_EOL;
    }
}
