<?php

namespace Bot\Commands;

use Bot\Attributes\Controller;
use Bot\Attributes\Validator;
use Bot\Cache\CacheAdapter;
use Bot\Exceptions\ValidationException;
use Exception;
use ReflectionClass;
use ReflectionException;
use Throwable;

class CommandAdapter
{
    private array $commands;

    /**
     * @throws ReflectionException
     */
    public function __construct(CacheAdapter $cacheAdapter)
    {
        $last = function (string $path): string {
            $arr = explode('\\', $path);
            return end($arr);
        };

        $validators = array();
        foreach ($this->getAllClassesInProject() as $class) {
            if (is_subclass_of($class, 'Bot\Validator\Validator') &&
                count((new ReflectionClass($class))->getAttributes(Validator::class)) > 0) {

                error_log('#v>' . $class . PHP_EOL);

                $validators[$last($class)] = new $class();
            }
        }

        $getValidator = fn(string $name) => $validators[$last($name)] ?? $validators['OkValidator'];

        $this->commands = array();
        foreach ($this->getAllClassesInProject() as $class) {
            if (is_subclass_of($class, 'Bot\Commands\Command') &&
                count((new ReflectionClass($class))->getAttributes(Controller::class)) > 0) {

                error_log('#c>' . $class . PHP_EOL);

                $this->addCommand(new $class($cacheAdapter, $getValidator($class . 'Validator'), $this));
            }
        }
    }

    private function getAllClassesInProject(): array
    {
        $allNames = preg_split('/[\r\n]+/', shell_exec('find ./src'));

        $getName = function(string $path): string
        {
            preg_match('/^\.\/src\/(.+)\.php$/', $path, $matches);

            if (str_ends_with($path, 'config.php') || count($matches) < 2)
            {
                return 'FAILED';
            }

            return 'Bot\\' . str_replace('/', '\\', $matches[1]);
        };

        $allNames = array_map($getName, $allNames);

        return $allNames;
    }

    public function addCommand(Command $command): void
    {
        $this->commands[trim(strtolower($command->getName()))] = $command;
    }

    public function addCommands(Command ...$commands): void
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
    public function getCommand(string $name): ?Command
    {
        try {
            return $this->commands[trim(strtolower($name))];
        } catch (Exception $e) {
            return null;
        }
    }

    public function executeCommand(string $name, int $user_id, array $args = array()): string
    {
        $command = $this->getCommand($name);

        $response = '';
        if ($command != null) {
            try {
                $response = $command->execute($user_id, $args);
            } catch (ValidationException $e) {
                $response = 'Validation failed: ' . $e->getMessage();
            } catch (Throwable $e) {
                error_log(var_export(get_class($e) . ' : ' . $e->getMessage() . PHP_EOL . $e->getFile() . ' ' . $e->getLine(), true));

                $response = var_export($e, true);
            }
        } else {
            $response = 'Command not found!';
        }

        return $response;
    }
}