<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


class RequestFacade extends \Astaroth\Support\Facades\Facade
{
    public static function request(string $method, array $parameters = [], ?string $token = null): array
    {
        return static::getObject("client")?->request($method, $parameters, $token);
    }
}