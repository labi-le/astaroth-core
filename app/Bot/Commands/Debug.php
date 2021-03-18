<?php
declare(strict_types=1);

namespace Manager\Commands;


/**
 * Trait Debug
 * @package Manager\Commands
 */
trait Debug
{

    private function print($data): void
    {
        var_dump($data);
        $this->vk->msg(print_r($data, true))->send();
    }
}