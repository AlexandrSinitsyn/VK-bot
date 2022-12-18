<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

#[Controller]
class GetHomeworkResultsCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'get-hw-results';
    }

    public function getDescription(): string
    {
        return 'Get homework\'s results.' . PHP_EOL . 'Usage example: `get-hw-results 1`' . PHP_EOL .
            'Usage regex: `get-hw-results\s+(\d+)\s*`';
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

        $result = $this->homeworkService->getHomeworkById($matches[1]);

        if ($result == null) {
            return 'Homework not found';
        }

        return join(' ', $result->results);
    }

    protected function register(array $user, array $args): string
    {
        return 'You can not get homeworks\' results til you are not registered';
    }
}