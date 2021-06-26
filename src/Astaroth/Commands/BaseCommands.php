<?php

declare(strict_types=1);

namespace Astaroth\Commands;


/**
 * Class BaseCommands
 * @package Astaroth\Commands
 */
abstract class BaseCommands
{
    protected function kick(int $id, int $chat_id): bool
    {
        return false;
    }
}