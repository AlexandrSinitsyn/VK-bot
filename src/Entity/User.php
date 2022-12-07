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
}
