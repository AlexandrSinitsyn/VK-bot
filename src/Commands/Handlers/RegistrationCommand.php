<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

const DEBUG = true;

#[Controller]
class RegistrationCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'Register';
    }

    public function getDescription(): string
    {
        return 'User registration.' . PHP_EOL . 'Usage example: `Register student.`.' . PHP_EOL . 'Usage regex: `Register\s+([Ss]tudent[.!]?)?\s*`';
    }

    protected function response(User $user, array $args): ?string
    {
        if (DEBUG) {
            return null;
        } else {
            return 'You can not re-register';
        }
    }

    protected function register(array $user, array $args): string
    {
        $is_student = preg_match('/^([Ss]tudent[.!]?)?$/', strtolower($args[0] ?? '')) === 1;

        $result = $this->userService->save_user($user['first_name'], $user['id'], $is_student);

        return $result ? 'Ok' : 'Sorry, smth failed';
    }
}