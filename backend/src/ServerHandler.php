<?php

namespace Bot;

use Bot\Cache\CacheAdapter;
use Bot\Commands\CommandsStorage;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

class ServerHandler extends VKCallbackApiServerHandler
{
    private VKApiClient $vkApi;
    private CommandsStorage $storage;
    private CacheAdapter $cacheAdapter;

    public function __construct()
    {
        $this->vkApi = new VKApiClient('5.130');
        $this->cacheAdapter = new CacheAdapter();
        $this->storage = new CommandsStorage($this->vkApi, $this->cacheAdapter);
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
            } catch (\Throwable $e) {
                error_log(var_export($e->getMessage() . PHP_EOL . $e->getFile() . ' ' . $e->getLine(), true));
                $this->vkApi->messages()->send(BOT_TOKEN, [
                    'user_id' => $user_id,
                    'random_id' => random_int(0, PHP_INT_MAX),
                    'message' => var_export($e, true)
                ]);
            }
        } else {
            $this->vkApi->messages()->send(BOT_TOKEN, [
                'user_id' => $user_id,
                'random_id' => random_int(0, PHP_INT_MAX),
                'message' => 'Command not found!'
            ]);
        }

        echo 'ok';
    }
}