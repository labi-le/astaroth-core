<?php


namespace Astaroth\Support\Facades;


use Astaroth\Interface\TokenChangeInterface;

class RequestFacade extends \Astaroth\Support\Facades\Facade implements TokenChangeInterface
{
    private static string $access_token;

    public static function request(string $method, array $parameters = [], ?string $token = null): array
    {
        $object = static::getObject(\Astaroth\VkUtils\Message::class);
        self::$access_token === null ?: $object->setDefaultToken(self::$access_token);
        return $object->request($method, $parameters, $token);

    }

    public static function changeToken(string $access_token): static
    {
        $singleton = new static();
        $singleton::$access_token = $access_token;
        return $singleton;
    }
}