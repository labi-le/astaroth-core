<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades\Message;


/**
 * Class MessageConstructor
 * @package Astaroth\Support\Facades\Message
 */
class MessageConstructor extends \Astaroth\Support\Facades\Facade
{
    public static function create(callable $func): array
    {
        return static::getObject("message")?->create($func(new \Astaroth\VkUtils\Builders\MessageBuilder()));
    }

}