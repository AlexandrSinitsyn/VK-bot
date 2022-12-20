<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

#[Controller]
class CheckHomeworkCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'check-hw';
    }

    public function getDescription(): string
    {
        return 'Add result to student\'s homework.' . PHP_EOL . 'Usage example: `check-hw hw=1: student=1 -> mark=5`' . PHP_EOL .
            'Usage regex: `check-hw\s+(hw\s*=|n\s*=)?\s*(\d+)\s*:\s*(student\s*=|s\s*=)?\s*(\d+)\s*->\s*(mark\s*=|m\s*=)?\s*(\d)\s*`';
    }

    protected function response(User $user, array $args): string
    {
        preg_match('/^(hw\s*=|n\s*=)?\s*(\d+)\s*:\s*(student\s*=|s\s*=)?\s*(\d+)\s*->\s*(mark\s*=|m\s*=)?\s*(\d)$/',
            trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isTeacher', $user))
            ->pipe(fn() => $this->validate('arguments', array('matches' => $matches, 'count' => 7)))
            ->pipe(fn() => $this->validate('homeworkId', (int) $matches[2]))
            ->pipe(fn() => $this->validate('studentId', (int) $matches[4]))
            ->pipe(fn() => $this->validate('mark', (int) $matches[6]))
            ->commit()->asFailure()?->onThrow();

        $result = $this->homeworkService->checkHomework($matches[2], $matches[4], $matches[6]);

        return $result ? 'Ok' : 'Sorry, smth failed';
    }
}