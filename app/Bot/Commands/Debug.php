<?php

declare(strict_types=1);


namespace Bot\Commands;


class Debug extends Commands
{
    public function pr()
    {
        $this->print($this->data->getRawData());
    }

    private function print(...$data): void
    {
        var_dump($data);

        $this->reply(print_r($data,true));
    }
}