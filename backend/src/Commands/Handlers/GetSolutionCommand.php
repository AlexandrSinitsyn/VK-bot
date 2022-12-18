<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

#[Controller]
class GetSolutionCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'get-hw-solution';
    }

    public function getDescription(): string
    {
        return 'Get student\'s homework solution.' . PHP_EOL . 'Usage example: `get-hw-solution 1: 1`' . PHP_EOL .
            'Usage regex: `get-hw-solution\s+(\d+):\s+(\d+)\s*`';
    }

    protected function response(User $user, array $args): ?string
    {
        if ($user->student) {
            return null;
        }

        preg_match('/^(\d+):\s+(\d+)$/', trim(join(' ', $args)), $matches);

        if (count($matches) < 3) {
            return "Invalid command use. Look in `help`";
        }

        $result = $this->homeworksSolutionService->getSolution($matches[1], $matches[2]);

        return strval($result ?? 'Solution not found');
    }

    protected function register(array $user, array $args): string
    {
        return 'You can not get homeworks til you are not registered';
    }
}