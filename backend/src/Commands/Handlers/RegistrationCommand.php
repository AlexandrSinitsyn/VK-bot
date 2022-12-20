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
        return 'User registration.' . PHP_EOL . 'Usage example: `Register student`.' . PHP_EOL . 'Usage regex: `Register\s+(student|teacher)\s*`';
    }

    protected function response(User $user, array $args): string
    {
        return 'You can not re-register';
    }

    protected function register(array $user, array $args): string
    {
        preg_match('/^(student|teacher)$/', strtolower(trim(join(' ', $args))), $matches);

        $this->validate('arguments', array('matches' => $matches, 'count' => 2))->asFailure()?->onThrow();

        $result = $this->userService->saveUser($user['first_name'], $user['id'], str_starts_with($matches[1], 's'));

        return $result ? 'Ok' : 'Sorry, smth failed';
    }
}