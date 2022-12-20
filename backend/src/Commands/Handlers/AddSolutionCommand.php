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
        return 'Add new homework solution.' . PHP_EOL . 'Usage example: `add-hw-solution hw=1: text=homework was really easy`' . PHP_EOL .
            'Usage regex: `add-hw-solution\s+(hw\s*=|n\s*=)?\s*(\d+)\s*:\s*(text\s*=|t\s*=)?\s*(.*)\s*`';
    }

    protected function response(User $user, array $args): string
    {
        preg_match('/^(hw\s*=|n\s*=)?\s*(\d+)\s*:\s*(text\s*=|t\s*=)?\s*(.*)$/',
            trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isStudent', $user))
            ->pipe(fn() => $this->validate('arguments', array('matches' => $matches, 'count' => 5)))
            ->pipe(fn() => $this->validate('homeworkId', (int) $matches[2]))
            ->pipe(fn() => $this->validate('unique', array('hwid' => (int) $matches[2], 'uid' => $user->id)))
            ->pipe(fn() => $this->validate('deadline', (int) $matches[2]))
            ->commit()->asFailure()?->onThrow();

        $result = $this->homeworksSolutionService->saveSolution($matches[2], $user->id, $matches[4]);

        return $result ? 'Ok' : 'Sorry, smth failed';
    }
}