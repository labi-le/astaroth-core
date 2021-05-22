<?php

declare(strict_types=1);

namespace Bot\Controller;

use Astaroth\VkUtils\Client;
use Bot\Models\DataParser;

class Controller
{
    protected static array $vk;

    public static function setClient(Client ...$client): void
    {
        static::$vk = $client;
    }

    /**
     * Вызов типа события и передача данных
     * @param DataParser $data
     */
    public function handle(DataParser $data): void
    {
        new TypeController($data);
    }

}