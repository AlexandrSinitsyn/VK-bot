<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;
use DateTime;
use Exception;

#[Controller]
class GetAllStudentsCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'get-all-students';
    }

    public function getDescription(): string
    {
        return 'Get all students\' list: student -> id.' . PHP_EOL . 'Usage example: `get-all-students`';
    }

    protected function response(User $user, array $args): ?string
    {
        if ($user->student) {
            return null;
        }

        $students = array_filter($this->userService->getAllUsers(), fn(User $u) => $u->student);

        return join(PHP_EOL, array_map(fn(User $u) => strval($u), $students));
    }

    protected function register(array $user, array $args): string
    {
        return 'You can not get all students til you are not a teacher';
    }
}