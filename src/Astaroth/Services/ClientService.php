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
    public function __invoke(ContainerBuilder $container)
    {
        $container
            ->register("client", Client::class)
            ->setLazy(true)
            ->addArgument($container->getParameter(Configuration::API_VERSION))
            ->addMethodCall("setDefaultToken", [$container->getParameter(Configuration::ACCESS_TOKEN)]);
    }
}