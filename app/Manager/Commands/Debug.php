<?php
declare(strict_types=1);

namespace Manager\Commands;

use Manager\Models\SimpleVKExtend;

/**
 * Trait Debug
 * @package Manager\Commands
 */
trait Debug
{

    private function print($data)
    {
        var_dump($data);
        $this->vk->msg(print_r($data, true))->send();
    }
}