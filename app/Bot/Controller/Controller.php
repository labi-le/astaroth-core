<?php

declare(strict_types=1);

namespace Bot\Controller;

use DigitalStars\SimpleVK\SimpleVK;
use Bot\Commands\Commands;

class Controller
{
    public static SimpleVK $vk;

    /**
     * Вызов типа события и передача данных
     * @param array $data
     * @param SimpleVK $bot
     */
    public static function handle(array $data, SimpleVK $bot): void
    {
        $type = $data['type'];
        if (method_exists(TypeController::class, $type)) {
            self::$vk = $bot;
            TypeController::$type($data);
        }

    }

    /**
     * Выполнить метод\методы
     * @param array|string $methods
     */
    public static function method_execute(array|string $methods): void
    {
        if (is_array($methods)) {
            foreach ($methods as $method) {
                if (Commands::set(self::$vk)->$method() === false) {
                    break;
                }
            }
        } else {
            Commands::set(self::$vk)->$methods();
        }
    }

}