<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Contracts\ConfigurationInterface;
use Astaroth\Contracts\ContainerPlaceholderInterface;
use Astaroth\VkUtils\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ClientContainer
 * @package Astaroth\Containers
 */
final class ClientContainer implements ContainerPlaceholderInterface
{
    public const CONTAINER_ID = "client";

    /**
     */
    public function __invoke(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $container
            ->register(self::CONTAINER_ID, Client::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()]);
    }
}
