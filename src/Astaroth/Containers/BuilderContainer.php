<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Contracts\ConfigurationInterface;
use Astaroth\Contracts\ContainerPlaceholderInterface;
use Astaroth\VkUtils\Builder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class BuilderContainer
 * @package Astaroth\Containers
 */
final class BuilderContainer implements ContainerPlaceholderInterface
{
    public const CONTAINER_ID = "builder";

    public function __invoke(ContainerBuilder $container, ConfigurationInterface $configuration): void
    {
        $container
            ->register(self::CONTAINER_ID, Builder::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()])
            ->addMethodCall("setParallelProcess", [$configuration->getCountParallelOperations()]);
    }
}
