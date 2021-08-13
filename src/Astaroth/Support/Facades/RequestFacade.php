<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Astaroth\VkUtils\Client;

final class RequestFacade
{
    private const SERVICE_ID = "client";

    /**
     * @throws \Throwable
     */
    public static function request(string $method, array $parameters = [], ?string $token = null): array
    {
        /**
         * @var $instance Client
         */
        $instance = FacadePlaceholder::getInstance()?->getContainer()->get(self::SERVICE_ID);
        return $instance->request($method, $parameters, $token);
    }
}