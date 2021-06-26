<?php


namespace Astaroth\Support\Facades\Message;


use Astaroth\VkUtils\Builders\MessageBuilder;

/**
 * Class MessageConstructor
 * @package Astaroth\Support\Facades\Message
 */
class MessageConstructor extends \Astaroth\Support\Facades\Facade
{
    public static function create(callable $func): array
    {
        return static::getObject(\Astaroth\VkUtils\Message::class)->create($func(new MessageBuilder()));
    }

}