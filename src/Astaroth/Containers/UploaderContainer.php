<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Astaroth\Auth\ParameterMissingException;
use Astaroth\Contracts\ContainerPlaceholderInterface;
use Astaroth\VkUtils\Uploader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class UploaderContainer
 * @package Astaroth\Containers
 */
final class UploaderContainer implements ContainerPlaceholderInterface
{
    public const CONTAINER_ID = "uploader";

    /**
     * @throws ParameterMissingException
     */
    public function __invoke(ContainerBuilder $container, Configuration $configuration): void
    {
        $container
            ->register(self::CONTAINER_ID, Uploader::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()])
            ->addMethodCall("setParallelProcess", [$configuration->getCountParallelOperations()]);
    }
}