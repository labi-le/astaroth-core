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
    public function __invoke(ContainerBuilder $container)
    {
        $container
            ->register("uploader",Uploader::class)
            ->setLazy(true)
            ->addArgument($container->getParameter(Configuration::API_VERSION))
            ->addMethodCall("setDefaultToken", [$container->getParameter(Configuration::ACCESS_TOKEN)]);
    }
}