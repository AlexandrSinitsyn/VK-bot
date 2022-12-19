<?php

namespace Bot;

use Bot\Cache\CacheAdapter;
use Bot\Commands\CommandsStorage;
use Bot\Exceptions\ValidationException;
use Throwable;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

const VK_API = new VKApiClient('5.130');

class ServerHandler extends VKCallbackApiServerHandler
{
    private CommandsStorage $storage;
    private CacheAdapter $cacheAdapter;

    public function __construct()
    {
        $this->cacheAdapter = new CacheAdapter();
        $this->storage = new CommandsStorage($this->cacheAdapter);
    }

    public static function sendMessage(int $user_id, string $message)
    {
        VK_API->messages()->send(BOT_TOKEN, [
            'user_id' => $user_id,
            'random_id' => random_int(0, PHP_INT_MAX),
            'message' => $message
        ]);
    }

    public static function getUsers(array $ids): array
    {
        return VK_API->users()->get(BOT_TOKEN, [
            'user_ids' => $ids
        ]);
    }

    function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === GROUP_SECRET && $group_id === GROUP_ID) {
            echo API_CONFIRMATION_TOKEN;
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        $message = $object['message'];
        $text = $message->text;
        $args = preg_split('/\s+/', $text);
        $user_id = $message->from_id;

        $command = $this->storage->getCommand(array_shift($args));
        if ($command != null) {
            try {
                $command->execute($user_id, $args);
            } catch (ValidationException $e) {
                self::sendMessage($user_id, 'Validation failed: ' . $e->getMessage());
            } catch (Throwable $e) {
                error_log(var_export(get_class($e) . ' : ' . $e->getMessage() . PHP_EOL . $e->getFile() . ' ' . $e->getLine(), true));

                self::sendMessage($user_id, var_export($e, true));
            }
        } else {
            self::sendMessage($user_id, 'Command not found!');
        }

        echo 'ok';
    }
}