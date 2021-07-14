<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


class UserRequestFacade extends \Astaroth\Support\Facades\Facade
{
    public static function request(string $method, array $parameters = [], ?string $token = null): array
    {
        return static::getObject("client")
            ?->setDefaultToken(self::$container->getParameter("USER_ACCESS_TOKEN"))
            ->request($method, $parameters, $token);
    }
}