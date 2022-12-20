<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

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

    protected function response(User $user, array $args): string
    {
        preg_match('/^(\d+):\s*(.*)\s*$/', trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isStudent', $user))
            ->pipe(fn() => $this->validate('arguments', $matches))
            ->pipe(fn() => $this->validate('homeworkId', (int) $matches[1]))
            ->pipe(fn() => $this->validate('unique', array('hwid' => (int) $matches[1], 'uid' => $user->id)))
            ->pipe(fn() => $this->validate('deadline', (int) $matches[1]))
            ->commit()->asFailure()?->onThrow();

        $result = $this->homeworksSolutionService->saveSolution($matches[1], $user->id, $matches[2]);

        return $result ? 'Ok' : 'Sorry, smth failed';
    }
}