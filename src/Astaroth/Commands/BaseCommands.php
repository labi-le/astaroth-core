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
}