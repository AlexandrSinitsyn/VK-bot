<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

#[Controller]
class GetHomeworkCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'get-hw';
    }

    public function getDescription(): string
    {
        return 'Get homework by id.' . PHP_EOL . 'Usage example: `get-hw hw=1`' . PHP_EOL .
            'Usage regex: `get-hw\s+(hw\s*=|n\s*=)?\s*(\d+)\s*`';
    }

    protected function response(User $user, array $args): string
    {
        preg_match('/^(hw\s*=|n\s*=)?\s*(\d+)$/', trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isStudent', $user))
            ->pipe(fn() => $this->validate('arguments', array('matches' => $matches, 'count' => 3)))
            ->commit()->asFailure()?->onThrow();

        $result = $this->homeworkService->getHomeworkById($matches[2]);

        return $result ?? 'Homework not found';
    }
}