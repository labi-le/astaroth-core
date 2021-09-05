<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Astaroth\VkUtils\Uploader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class UploaderContainer
 * @package Astaroth\Services
 */
class UploaderContainer implements ContainerInterface
{
    public const SERVICE_ID = "uploader";

    public function __invoke(ContainerBuilder $container, Configuration $configuration): void
    {
        $container
            ->register(self::SERVICE_ID, Uploader::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()]);
    }
}