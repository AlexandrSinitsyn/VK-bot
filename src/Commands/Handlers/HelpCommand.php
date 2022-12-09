<?php /** @noinspection PhpUnused */

namespace Bot\Commands\Handlers;

use Bot\Attributes\Controller;
use Bot\Entity\User;

#[Controller]
class HelpCommand extends AbstractCommand
{
    public function getName(): string
    {
        return "Help";
    }

    public function getDescription(): string
    {
        return 'All commands\' registry with descriptions.' . PHP_EOL . 'Usage: `Help`';
    }

    private function helpString(): string
    {
        $cmds = $this->getCommandStorage()->getCommands();
        $cmds = array_values($cmds);
        $result = "";

        foreach ($cmds as $cmd) {
            $result .= "-" . "\t" . $cmd->getName() . "\t-\t" . $cmd->getDescription() . PHP_EOL . PHP_EOL;
        }

        return $result;
    }

    protected function response(User $user, array $args): ?string
    {
        return self::helpString();
    }

    protected function register(array $user, array $args): string
    {
        return self::helpString();
    }
}