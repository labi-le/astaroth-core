<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Astaroth\Services\ClientService;
use Astaroth\VkUtils\Client;

final class RequestFacade
{
    /**
     * @throws \Throwable
     */
    public static function request(string $method, array $parameters = [], ?string $token = null): array
    {
        /**
         * @var Client $instance
         */
        $instance = FacadePlaceholder::getInstance()->getContainer()->get(ClientService::SERVICE_ID);
        return $instance->request($method, $parameters, $token);
    }
}