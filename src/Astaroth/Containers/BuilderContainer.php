<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Astaroth\Auth\ParameterMissingException;
use Astaroth\VkUtils\Builder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class BuilderContainer
 * @package Astaroth\Containers
 */
class BuilderContainer implements ContainerInterface
{
    public const CONTAINER_ID = "builder";

    /**
     * @throws ParameterMissingException
     */
    public function __invoke(ContainerBuilder $container, Configuration $configuration): void
    {
        $container
            ->register(self::CONTAINER_ID, Builder::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()]);
    }
}