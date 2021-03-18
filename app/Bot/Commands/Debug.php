<?php
declare(strict_types=1);

namespace Bot\Commands;


/**
 * Trait Debug
 * @package Bot\Commands
 */
trait Debug
{

    private function print($data): void
    {
        var_dump($data);
        $this->vk->msg(print_r($data, true))->send();
    }
}