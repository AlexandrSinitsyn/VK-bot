<?php

namespace Bot\Repository;

use Bot\Attributes\Repository;
use Bot\Entity\User;

#[Repository]
interface UserRepository
{
    public function getAllUsers(): array;
    public function getUserById(int $id): ?User;
    public function saveUser(User $user): bool;
}