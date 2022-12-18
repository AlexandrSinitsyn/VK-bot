<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;
use DateTime;
use Exception;

#[Controller]
class CheckHomeworkCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'check-hw';
    }

    public function getDescription(): string
    {
        return 'Add result to student\'s homework.' . PHP_EOL . 'Usage example: `check-hw 1: 1 -> 5`' . PHP_EOL .
            'Usage regex: `check-hw\s+(\d+):\s*(\d+)\s+->\s+(\d+)\s*`';
    }

    protected function response(User $user, array $args): ?string
    {
        if ($user->student) {
            return null;
        }

        preg_match('/^(\d+):\s*(\d+)\s+->\s+(\d+)$/', trim(join(' ', $args)), $matches);

        if (count($matches) < 4) {
            return "Invalid command use. Look in `help`";
        }

        try {
            $result = $this->homeworkService->checkHomework($matches[1], $matches[2], $matches[3]);

            return $result ? 'Ok' : 'Sorry, smth failed';
        } catch (Exception $e) {
            return "Failed: $e";
        }
    }

    protected function register(array $user, array $args): string
    {
        return 'You can not add homework\'s results til you are not a teacher';
    }
}