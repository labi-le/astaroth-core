<?php
declare(strict_types=1);

namespace Astaroth\Commands;

use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\Support\Facades\Request;
use Astaroth\VkUtils\Contracts\IBuilder;
use Throwable;

final class ApiRequest
{
    public function __construct(private ?string $accessToken)
    {
    }

    /**
     * @throws Throwable
     */
    private function call(string $method, $params): array
    {
        return Request::call($method, $params, $this->accessToken);
    }

    /**
     * @param MessageEvent $data
     * @param array $event
     * @return array
     *
     * @throws Throwable
     * @see https://vk.com/dev/messages.sendMessageEventAnswer
     * @noinspection JsonEncodingApiUsageInspection
     */
    public function sendMessageEventAnswer(MessageEvent $data, array $event): array
    {
        return $this->call("messages.sendMessageEventAnswer",
            [
                "event_id" => $data->getEventId(),
                "user_id" => $data->getUserId(),
                "peer_id" => $data->getPeerId(),
                "event_data" => json_encode($event)
            ]);
    }

    /**
     * @param IBuilder $message
     * @param int|null $conversation_message_id
     * @param int|null $message_id
     * @return array
     *
     * @throws Throwable
     * @see https://vk.com/dev/messages.edit
     */
    public function messagesEdit(IBuilder $message, int $conversation_message_id = null, int $message_id = null): array
    {
        $params = $message->getParams();
        $params["peer_id"] = $params["peer_ids"];
        $params["conversation_message_id"] = $conversation_message_id;
        $params["message_id"] = $message_id;

        return $this->call("messages.edit", $params);
    }

    /**
     * @param int[] $message_ids
     * @param bool $spam
     * @param int|null $group_id
     * @param bool $delete_for_all
     * @param int|null $peer_id
     * @param int[] $conversation_message_ids
     * @return array
     *
     * @throws Throwable
     * @see https://vk.com/dev/messages.delete
     */
    public function messagesDelete
    (
        array $message_ids = [],
        bool  $spam = false,
        int   $group_id = null,
        bool  $delete_for_all = false,
        int   $peer_id = null,
        array $conversation_message_ids = [],
    ): array
    {
        $params = [];
        $params["message_ids"] = implode(",", $message_ids);
        $params["conversation_message_ids"] = implode(",", $conversation_message_ids);
        $params["peer_id"] = $peer_id;
        $params["spam"] = $spam;
        $params["group_id"] = $group_id;
        $params["delete_for_all"] = $delete_for_all;

        return $this->call("messages.delete", $params);
    }

    /**
     * @param int $chat_id
     * @param int $id
     * @return array
     *
     * @throws Throwable
     * @see https://vk.com/dev/messages.removeChatUser
     */
    public function kick(int $chat_id, int $id): array
    {
        return $this->call("messages.removeChatUser", ["chat_id" => $chat_id, "member_id" => $id]);
    }

    /**
     * @param int[] $user_ids
     * @param string[] $fields
     * @param string $name_case
     * @return array
     *
     * @throws Throwable
     * @see https://vk.com/dev/users.get
     */
    public function usersGet(array $user_ids, array $fields = [], string $name_case = "nom"): array
    {
        return $this->call("users.get",
            [
                "user_ids" => implode(",", $user_ids),
                "fields" => implode(",", $fields),
                "name_case" => $name_case
            ]
        );
    }

    /**
     * @param int[] $group_ids
     * @param string[] $fields
     * @return array
     * @throws Throwable
     *
     * @see https://vk.com/dev/groups.getById
     */
    public function groupsGetById(array $group_ids, array $fields = []): array
    {
        return $this->call("groups.getById",
            [
                "group_ids" => implode(",", $group_ids),
                "fields" => implode(",", $fields)
            ]
        );
    }

    /**
     * @param string $url
     * @param bool $private
     * @return array
     * @throws Throwable
     * @see https://vk.com/dev/utils.getShortLink
     */
    public function utilsGetShortLink(string $url, bool $private = false): array
    {
        return $this->call("utils.getShortLink", ["url" => $url, "private" => $private]);
    }
}