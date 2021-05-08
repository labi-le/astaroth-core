<?php

declare(strict_types=1);

namespace Bot\Controller;

use DigitalStars\SimpleVK\SimpleVK;
use Bot\Commands\Commands;

class Controller
{
    protected SimpleVK $vk;

    public function setVK(SimpleVK $vk): static
    {
        $this->vk = $vk;
        return $this;
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