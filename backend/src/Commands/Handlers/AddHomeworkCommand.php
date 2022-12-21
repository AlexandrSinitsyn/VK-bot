<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;
use DateTime;

#[Controller]
class AddHomeworkCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'add-hw';
    }

    public function getDescription(): string
    {
        return 'Add new homework.' . PHP_EOL . 'Usage example: `add-hw hw=1: deadline=01-01-2023`' . PHP_EOL .
            'Usage regex: `add-hw\s+(hw\s*=|n\s*=)?\s*(\d+)\s*:\s*(deadline\s*=|d\s*=)?\s*(\d{1,2}([-\/])\d{1,2}\5\d{2,4})\s*`';
    }

    protected function response(User $user, array $args): string
    {
        preg_match('/^(hw\s*=|n\s*=)?\s*(\d+)\s*:\s*(deadline\s*=|d\s*=)?\s*(\d{1,2}([-\/])\d{1,2}\5\d{2,4})$/',
            trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isTeacher', $user))
            ->pipe(fn() => $this->validate('arguments', array('matches' => $matches, 'count' => 6)))
            ->pipe(fn() => $this->validate('date', $matches[4]))
            ->pipe(fn() => $this->validate('unique', (int) $matches[2]))
            ->commit()->asFailure()?->onThrow();

        /** @noinspection PhpUnhandledExceptionInspection */
        $result = $this->homeworkService->saveHomework($matches[2], array(), new DateTime($matches[4]));

        return $result ? 'Ok' : 'Sorry, smth failed';
    }
}