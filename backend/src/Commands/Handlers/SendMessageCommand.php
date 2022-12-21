<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;
use Bot\ServerHandler;

#[Controller]
class SendMessageCommand extends AbstractCommand
{
    public function getName(): string
    {
        return 'send-message';
    }

    public function getDescription(): string
    {
        return 'Send message to a student by his id.' . PHP_EOL . 'Usage example: `send-message student=1: text=Your homework was really good!`' . PHP_EOL .
            'Usage regex: `add-hw-solution\s+(student\s*=|s\s*=|id\s*=)?\s*(\d+)\s*:\s*(text\s*=|t\s*=)?\s*(.*)\s*`';
    }

    protected function response(User $user, array $args): string
    {
        preg_match('/^(student\s*=|s\s*=|id\s*=)?\s*(\d+)\s*:\s*(text\s*=|t\s*=)?\s*(.*)$/',
            trim(join(' ', $args)), $matches);

        $this->transaction()
            ->pipe(fn() => $this->validate('isTeacher', $user))
            ->pipe(fn() => $this->validate('arguments', array('matches' => $matches, 'count' => 5)))
            ->pipe(fn() => $this->validate('studentId', (int) $matches[2]))
            ->commit()->asFailure()?->onThrow();

        ServerHandler::sendMessage((int) $matches[2], 'From `' . $user->name . '`: ' . $matches[4]);
        echo 'ok';

        return 'Successfully sent';
    }
}