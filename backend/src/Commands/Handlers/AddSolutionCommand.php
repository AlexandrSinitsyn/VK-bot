<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;
use DateTime;
use Exception;

#[Controller]
class AddSolutionCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'add-hw-solution';
    }

    public function getDescription(): string
    {
        return 'Add new homework solution.' . PHP_EOL . 'Usage example: `add-hw-solution 1: homework was really easy`' . PHP_EOL .
            'Usage regex: `add-hw-solution\s+(\d+):\s*(.*)\s*`';
    }

    protected function response(User $user, array $args): ?string
    {
        if (!$user->student) {
            return null;
        }

        preg_match('/^(\d+):\s*(.*)\s*$/', trim(join(' ', $args)), $matches);

        if (count($matches) < 3) {
            return "Invalid command use. Look in `help`";
        }

        try {
            $result = $this->homeworksSolutionService->saveSolution($matches[1], $user->id, $matches[2]);

            return $result ? 'Ok' : 'Sorry, smth failed';
        } catch (Exception $e) {
            return "Failed: $e";
        }
    }

    protected function register(array $user, array $args): string
    {
        return 'You can not add homeworks til you are not a teacher';
    }
}