<?php


namespace Astaroth\Support\Facades;


class RequestFacade extends \Astaroth\Support\Facades\Facade
{
    public static function request(string $method, array $parameters = [], ?string $token = null):array
    {
        return static::getObject(\Astaroth\VkUtils\Message::class)->request($method, $parameters, $token);
    }
}