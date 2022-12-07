<?php

namespace Bot;

use Bot\Commands\Command;
use Bot\Commands\CommandsStorage;
use Bot\Commands\Handlers\HelloCommand;
use Bot\Commands\Handlers\RegistrationCommand;
use Bot\Database\DatabaseHandler;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

class ServerHandler extends VKCallbackApiServerHandler
{
    private VKApiClient $vkApi;
    private CommandsStorage $storage;
    private DatabaseHandler $databaseHandler;

    public function __construct()
    {
        $this->vkApi = new VKApiClient("5.130");
        $this->storage = new CommandsStorage(
            new HelloCommand($this->vkApi),
            new RegistrationCommand($this->vkApi),
        );
        $this->databaseHandler = new DatabaseHandler();
    }

    function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === GROUP_SECRET && $group_id === GROUP_ID) {
            echo API_CONFIRMATION_TOKEN;
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        $message = $object["message"];
        $text = $message->text;
        $args = preg_split("/\s+/", $text);
        $user_id = $message->from_id;

        $command = $this->storage->getCommand(array_shift($args));
        if ($command != null) {
            $command->execute($user_id, $args);
        } else {
            $this->vkApi->messages()->send(BOT_TOKEN, [
                "user_id" => $user_id,
                "random_id" => random_int(0, PHP_INT_MAX),
                "message" => "Command not found!"
            ]);
        }

        echo "ok";
    }
}