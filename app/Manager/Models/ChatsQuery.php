<?php

declare(strict_types=1);


namespace Manager\Models;


class ChatsQuery extends QueryBuilder
{

    /**
     * Название идентификатора
     */
    const ID = 'id';

    /**
     * Стандартные настройки для базы данных
     * https://sleekdb.github.io/#/configurations
     */
    const CONFIGURATION_DB =
        [
            "auto_cache" => false,
            "cache_lifetime" => null,
            "timeout" => 120,
            "primary_key" => self::ID
        ];

    protected string $store_name = 'chats';


    protected function _generateTable(int $id): array
    {
        // TODO: Implement _generateTable() method.
    }
}