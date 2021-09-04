<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Astaroth\Auth\Configuration;
use Astaroth\VkUtils\Uploader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class UploaderService
 * @package Astaroth\Services
 */
class UploaderService implements ServiceInterface
{
    public const SERVICE_ID = "uploader";

    public function __invoke(ContainerBuilder $container)
    {
        $container
            ->register(self::SERVICE_ID,Uploader::class)
            ->setLazy(true)
            ->addArgument($container->getParameter(Configuration::API_VERSION))
            ->addMethodCall("setDefaultToken", [$container->getParameter(Configuration::ACCESS_TOKEN)]);
    }
}