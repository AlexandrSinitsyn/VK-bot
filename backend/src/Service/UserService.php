<?php

namespace Bot\Service;

use Bot\Attributes\Service;
use Bot\Entity\User;
use Bot\Repository\Impl\UserRepositoryImpl;
use Bot\Repository\UserRepository;

#[Service]
class UserService
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepositoryImpl();
    }

    public function getAllUsers(): array
    {
        return $this->repository->getAllUsers();
    }

    public function getUserById(int $id): ?User
    {
        return $this->repository->getUserById($id);
    }

    public function saveUser(string $name, int $id, bool $isStudent): bool
    {
        return $this->repository->saveUser(new User($name, $id, $isStudent));
    }
}
