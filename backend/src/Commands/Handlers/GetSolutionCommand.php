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

    protected function response(User $user, array $args): string
    {
        preg_match('/^(\d+):\s+(\d+)$/', trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isTeacher', $user))
            ->pipe(fn() => $this->validate('arguments', $matches))
            ->pipe(fn() => $this->validate('homeworkExists', (int) $matches[1]))
            ->pipe(fn() => $this->validate('solutionExists', array('hwid' => (int) $matches[1], 'uid' => (int) $matches[2])))
            ->pipe(fn() => $this->validate('notChecked', array('hwid' => (int) $matches[1], 'uid' => (int) $matches[2])))
            ->commit()->asFailure()?->onThrow();

        return strval($this->homeworksSolutionService->getSolution($matches[1], $matches[2]));
    }
}