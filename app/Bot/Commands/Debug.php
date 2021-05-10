<?php

declare(strict_types=1);


namespace Bot\Commands;


class Debug extends Commands
{
    public function pr()
    {
//        $this->print(2121212121);
    }
    private function print(...$data): void
    {
        var_dump($data);
        $this->vk->msg(print_r($data, true))->send();
    }
}