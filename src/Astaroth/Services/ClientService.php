<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Astaroth\VkUtils\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ClientService
 * @package Astaroth\Services
 */
class ClientService
{
    public function __invoke(ContainerBuilder $container)
    {
        $container
            ->register("client", Client::class)
            ->setLazy(true)
            ->addArgument($container->getParameter("API_VERSION"))
            ->addMethodCall("setDefaultToken", [$container->getParameter("ACCESS_TOKEN")]);
    }
}