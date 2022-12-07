<?php

namespace Bot\Commands\Handlers;

use Bot\Entity\User;

class RegistrationCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'register';
    }

    public function getDescription(): string
    {
        return 'User registration';
    }

    protected function response(User $user, array $args): string
    {
        return 'You can not re-register';
    }

    protected function register(array $user, array $args): string
    {
        $is_student = preg_match('/([Ss]tudent[.!]?)?/', $args[0] ?? '') === 1;

        $result = $this->userService->save_user(new User($user['first_name'], $user['id'], $is_student));

        return $result ? "Ok" : "Sorry, smth failed";
    }
}