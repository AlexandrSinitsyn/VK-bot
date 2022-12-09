<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;
use DateTime;
use Exception;

#[Controller]
class AddHomeworkCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'add-hw';
    }

    public function getDescription(): string
    {
//        return 'Add new homework.' . PHP_EOL . 'Usage example: `add-hw 1: 00:00 01:01:2023`' . PHP_EOL .
//            'Usage regex: `add-hw\s+(\d+):\s*[0-9]{2}:[0-9]{2}\s+[0-9]{2}-[0-9]{2}-[0-9]{4}\s*`';
        return 'Add new homework.' . PHP_EOL . 'Usage example: `add-hw 1: 01-01-2023`' . PHP_EOL .
            'Usage regex: `add-hw\s+(\d+):\s*[0-9]{2}-[0-9]{2}-[0-9]{4}\s*`';
    }

    protected function response(User $user, array $args): ?string
    {
        if ($user->student) {
            return null;
        }

        preg_match('/^(\d+):\s*([0-9]{2}-[0-9]{2}-[0-9]{4})$/', trim(join(' ', $args)), $matches);

        try {
            $result = $this->homeworkService->save_homework($matches[1], array(), new DateTime($matches[2]));

            return $result ? "Ok" : "Sorry, smth failed";
        } catch (Exception $e) {
            return "Failed: $e";
        }
    }

    protected function register(array $user, array $args): string
    {
        return "You can not add homeworks til you are not a teacher";
    }
}