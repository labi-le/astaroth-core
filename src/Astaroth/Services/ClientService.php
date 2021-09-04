<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Astaroth\Auth\Configuration;
use Astaroth\VkUtils\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ClientService
 * @package Astaroth\Services
 */
class ClientService implements ServiceInterface
{
    public const SERVICE_ID = "client";

    public function __invoke(ContainerBuilder $container, Configuration $configuration): void
    {
        $container
            ->register(self::SERVICE_ID, Client::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()]);
    }
}