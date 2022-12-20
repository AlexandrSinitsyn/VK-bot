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
        return 'Get homework\'s results.' . PHP_EOL . 'Usage example: `get-hw-results hw=1`' . PHP_EOL .
            'Usage regex: `get-hw-results\s+(\d+)\s*`';
    }

    protected function response(User $user, array $args): string
    {
        preg_match('/^(hw\s*=|n\s*=)?\s*(\d+)$/', trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isStudent', $user))
            ->pipe(fn() => $this->validate('arguments', array('matches' => $matches, 'count' => 3)))
            ->pipe(fn() => $this->validate('homeworkExists', (int) $matches[2]))
            ->commit()->asFailure()?->onThrow();

        return join(' ', $this->homeworkService->getHomeworkById($matches[2])->results);
    }
}