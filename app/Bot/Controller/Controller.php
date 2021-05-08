<?php

declare(strict_types=1);

namespace Bot\Controller;

use DigitalStars\SimpleVK\SimpleVK;
use Bot\Commands\Commands;

class Controller
{
    /**
     * Вызов типа события и передача данных
     * @param array $data
     * @param SimpleVK $bot
     */
    public function handle(array $data, SimpleVK $bot): void
    {
        $type = $data['type'];
        Commands::setAuth($bot);

        new TypeController($type, $data);
    }

}