<?php

namespace Bot\Commands;

interface Command
{
    public function getName(): string;

    public function getDescription(): string;

    public function execute(int $user_id, array $args): void;
}