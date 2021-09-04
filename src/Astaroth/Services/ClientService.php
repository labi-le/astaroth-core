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

    public function __invoke(ContainerBuilder $container)
    {
        $container
            ->register(self::SERVICE_ID, Client::class)
            ->setLazy(true)
            ->addArgument($container->getParameter(Configuration::API_VERSION))
            ->addMethodCall("setDefaultToken", [$container->getParameter(Configuration::ACCESS_TOKEN)]);
    }
}