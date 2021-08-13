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
    /**
     * @param int $chat_id
     * @param int $id
     * @return array
     * @throws \Throwable
     * @see https://vk.com/dev/messages.removeChatUser
     */
    protected function kick(int $chat_id, int $id): array
    {
        return RequestFacade::request("messages.removeChatUser", ["chat_id" => $chat_id, "member_id" => $id]);
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
        return RequestFacade::request("users.get", ["user_ids" => $user_ids, "fields" => $fields, "name_case" => $name_case]);
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
        return RequestFacade::request("groups.getById", ["group_ids" => $group_id, "fields" => $fields]);
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
        return RequestFacade::request("utils.getShortLink", ["url" => $url, "private" => $private]);
    }

    protected function logToMessage(int $id, string $error_level, \Exception|string $e): void
    {
        \Astaroth\Foundation\Utils::logToMessage($id, $error_level, $e);
    }
}