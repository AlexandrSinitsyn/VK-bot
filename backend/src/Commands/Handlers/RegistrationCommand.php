<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

#[Controller]
class RegistrationCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'Register';
    }

    public function getDescription(): string
    {
        return 'User registration.' . PHP_EOL . 'Usage example: `Register student.`.' . PHP_EOL . 'Usage regex: `Register\s+(([Ss]tudent[.!]?)?|[Tt]eacher[.!])\s*`';
    }

    protected function response(User $user, array $args): ?string
    {
        return 'You can not re-register';
    }

    protected function register(array $user, array $args): string
    {
        $arg = strtolower($args[0] ?? '');

        $this->validate('command', $arg)->asFailure()?->onThrow();

        $result = $this->userService->saveUser($user['first_name'], $user['id'], !str_starts_with($arg, 't'));

        return $result ? 'Ok' : 'Sorry, smth failed';
    }
}