<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Astaroth\VkUtils\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ClientContainer
 * @package Astaroth\Containers
 */
class ClientContainer implements ContainerInterface
{
    public const CONTAINER_ID = "client";

    public function __invoke(ContainerBuilder $container, Configuration $configuration): void
    {
        $container
            ->register(self::CONTAINER_ID, Client::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()]);
    }
}