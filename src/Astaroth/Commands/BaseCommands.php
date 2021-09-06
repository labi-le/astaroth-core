<?php

declare(strict_types=1);

namespace Astaroth\Commands;


use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Foundation\Utils;
use Astaroth\Support\Facades\Create;
use Astaroth\Support\Facades\Request;
use Astaroth\VkUtils\Builders\Message;
use Astaroth\VkUtils\Contracts\IBuilder;

/**
 * Class BaseCommands
 * @package Astaroth\Commands
 */
abstract class BaseCommands
{

    /**
     * @throws \Throwable
     */
    protected function message(int $peer_id, string $text = null, array $attachment = [], string $access_token = null): array
    {
        $message = (new Message())
            ->setPeerId($peer_id)
            ->setMessage($text)
            ->setAttachment(...$attachment);

        if ($access_token) {
            return Create::changeToken($access_token)->create($message);
        }

        return Create::new($message);
    }

    /**
     * @param MessageNew|MessageEvent $data
     * @param array $event
     * @return array
     * @throws \Throwable
     * @see https://vk.com/dev/messages.sendMessageEventAnswer
     */
    protected function sendMessageEventAnswer(MessageNew|MessageEvent $data, array $event): array
    {
        return Request::call("messages.sendMessageEventAnswer",
            [
                "event_id" => $data->getEventId(),
                "user_id" => $data->getUserId(),
                "peer_id" => $data->getPeerId(),
                "event_data" => json_encode($event)
            ]
        );
    }

    /**
     * @param IBuilder $message
     * @param int|null $conversation_message_id
     * @param int|null $message_id
     * @return array
     * @throws \Throwable
     * @see https://vk.com/dev/messages.edit
     */
    protected function messagesEdit(IBuilder $message, int $conversation_message_id = null, int $message_id = null): array
    {
        $params = $message->getParams();
        $params["peer_id"] = $params["peer_ids"];
        $params["conversation_message_id"] = $conversation_message_id;
        $params["message_id"] = $message_id;

        return Request::call("messages.edit", $params);
    }

    /**
     * @param int $chat_id
     * @param int $id
     * @return array
     * @throws \Throwable
     * @see https://vk.com/dev/messages.removeChatUser
     */
    protected function kick(int $chat_id, int $id): array
    {
        return Request::call("messages.removeChatUser", ["chat_id" => $chat_id, "member_id" => $id]);
    }

    /**
     * @param int|string $user_ids
     * @param string|null $fields
     * @param string $name_case
     * @return array
     * @throws \Throwable
     * @see https://vk.com/dev/users.get
     */
    protected function usersGet(int|string $user_ids, string $fields = null, string $name_case = "nom"): array
    {
        return Request::call("users.get", ["user_ids" => $user_ids, "fields" => $fields, "name_case" => $name_case]);
    }

    /**
     * @param int|string $group_id
     * @param string|null $fields
     * @return array
     * @throws \Throwable
     * @see https://vk.com/dev/groups.getById
     */
    protected function groupsGetById(int|string $group_id, string $fields = null): array
    {
        return Request::call("groups.getById", ["group_ids" => $group_id, "fields" => $fields]);
    }

    /**
     * @param string $url
     * @param bool $private
     * @return array
     * @throws \Throwable
     * @see https://vk.com/dev/utils.getShortLink
     */
    protected function utilsGetShortLink(string $url, bool $private = false): array
    {
        return Request::call("utils.getShortLink", ["url" => $url, "private" => $private]);
    }

    /**
     * @throws \Throwable
     */
    protected function logToMessage(int $id, string $error_level, \Exception|string $e): void
    {
        Utils::logToMessage($id, $error_level, $e);
    }
}