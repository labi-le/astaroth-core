<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Containers\ClientContainer;
use Astaroth\VkUtils\Client;
use Throwable;

final class Request extends AbstractFacade
{
    /**
     * @param string $method
     * @param array $parameters
     * @param string|null $token
     * @return array
     * @throws Throwable
     */
    public static function call(string $method, array $parameters = [], ?string $token = null): array
    {
        return self::getContainerService()->request($method, $parameters, $token);
    }

    /**
     * @param string $method
     * @param array $parameters
     * @param string|null $token
     * @return array
     * @throws Throwable
     */
    public function __invoke(string $method, array $parameters = [], ?string $token = null): array
    {
        return self::call($method, $parameters, $token);
    }

    protected static function getServiceName(): string
    {
        return ClientContainer::CONTAINER_ID;
    }

    protected static function getContainerService(): Client
    {
        /**
         * @var Client $container
         */
        $container = parent::getContainerService();

        return $container;
    }
}
