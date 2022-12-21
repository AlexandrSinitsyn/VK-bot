<?php /** @noinspection PhpUnused */

namespace Bot;

use Bot\Cache\CacheAdapter;
use Bot\Commands\CommandAdapter;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

const VK_API = new VKApiClient('5.130');

class ServerHandler extends VKCallbackApiServerHandler
{
    private CommandAdapter $commandAdapter;
    private CacheAdapter $cacheAdapter;
    private static int $senderId;
    private static int $messageId;

    public function __construct()
    {
        $this->cacheAdapter = new CacheAdapter();
        $this->commandAdapter = new CommandAdapter($this->cacheAdapter);
    }

    public static function sendMessage(int $user_id, string $message)
    {
        VK_API->messages()->send(BOT_TOKEN, [
            'user_id' => $user_id,
            'random_id' => self::$messageId, // random_int(0, PHP_INT_MAX),
            'message' => $message,
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
        if ($secret != GROUP_SECRET) {
            echo 'nok';
            return;
        }

        $message = $object['message'];
        $text = $message->text;
        $args = preg_split('/\s+/', $text);
        $user_id = $message->from_id;
        self::$senderId = $user_id;
        self::$messageId = $message->id;

        $response = $this->commandAdapter->executeCommand(array_shift($args), $user_id, $args);

        self::sendMessage($user_id, $response);

        echo 'ok';
    }
}