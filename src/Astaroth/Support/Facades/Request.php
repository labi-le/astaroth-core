<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Astaroth\Containers\ClientContainer;
use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\VkUtils\Client;

final class Request
{
    /**
     * @param string $method
     * @param array $parameters
     * @param string|null $token
     * @return array
     * @throws \Throwable
     */
    public static function call(string $method, array $parameters = [], ?string $token = null): array
    {
        /**
         * @var Client $instance
         */
        $instance = FacadePlaceholder::getInstance()->getContainer()->get(ClientContainer::CONTAINER_ID);
        return $instance->request($method, $parameters, $token);
    }

    /**
     * @param string $method
     * @param array $parameters
     * @param string|null $token
     * @return array
     * @throws \Throwable
     */
    public function __invoke(string $method, array $parameters = [], ?string $token = null): array
    {
        return self::call($method, $parameters, $token);
    }

}