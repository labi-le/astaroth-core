<?php

declare(strict_types=1);

namespace Bot\Controller;

use DigitalStars\SimpleVK\SimpleVK;
use Bot\Commands\Commands;

class Controller
{
    protected static SimpleVK $vk;

    public static function setVK(SimpleVK $vk): void
    {
        static::$vk = $vk;
    }

    /**
     * Вызов типа события и передача данных
     * @param array $data
     */
    public function handle(array $data): void
    {
        $type = $data['type'];
        new TypeController($type, $data);
    }

}