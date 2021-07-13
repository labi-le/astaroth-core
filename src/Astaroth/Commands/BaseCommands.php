<?php

declare(strict_types=1);

namespace Astaroth\Commands;


use Astaroth\Support\Facades\RequestFacade;

/**
 * Class BaseCommands
 * @package Astaroth\Commands
 */
abstract class BaseCommands
{
    protected function kick(int $chat_id, int $id): array
    {
        return RequestFacade::request("messages.removeChatUser", ["chat_id" => $chat_id, "member_id" => $id]);
    }

    protected function usersGet(int|string $user_ids, string $fields, string $name_case = "nom"): array
    {
        return RequestFacade::request("users.get", ["user_ids" => $user_ids, "fields" => $fields, "name_case" => $name_case]);
    }

    protected function utilsGetShortLink(string $url, bool $private = false): array
    {
        return RequestFacade::request("utils.getShortLink", ["url" => $url, "private" => $private]);
    }

    protected function logToMessage(int $id, string $error_level, \Exception|string $e): void
    {
        \Astaroth\Support\Facades\Message\MessageConstructor::create(static function (\Astaroth\VkUtils\Contracts\IMessageBuilder $message) use ($id, $e, $error_level) {

            if ($e instanceof \Exception){
                return $message->setMessage(
                    sprintf(
                        "Logger:\nError Level - %s\nError Code - %s\nMessage - %s",
                        $error_level,
                        $e->getCode(),
                        $e->getMessage()
                    ))
                    ->setPeerId($id);
            }

            return $message->setMessage(
                sprintf(
                    "Logger:\nError Level - %s\nMessage - %s",
                    $error_level,
                    $e
                ))
                ->setPeerId($id);

        });
    }
}