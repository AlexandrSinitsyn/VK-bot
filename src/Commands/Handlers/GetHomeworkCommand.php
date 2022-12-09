<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

#[Controller]
class GetHomeworkCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'get-hw';
    }

    public function getDescription(): string
    {
        return 'Get homework by id.' . PHP_EOL . 'Usage example: `get-hw 1`' . PHP_EOL .
            'Usage regex: `get-hw\s+(\d+)\s*`';
    }

    protected function response(User $user, array $args): ?string
    {
        if (!$user->student) {
            return null;
        }

        preg_match('/^(\d+)$/', trim(join(' ', $args)), $matches);

        if (count($matches) < 2) {
            return "Invalid command use. Look in `help`";
        }

        $result = $this->homeworkService->get_homework_by_id($matches[1]);

        return $result ?? 'Homework not found';
    }

    protected function register(array $user, array $args): string
    {
        return 'You can not get homeworks til you are not registered';
    }
}