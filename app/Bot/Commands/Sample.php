<?php

declare(strict_types=1);


namespace Bot\Commands;


class Sample extends Commands
{
    public function hi(): void
    {
        $this->reply('пивет');
    }
}