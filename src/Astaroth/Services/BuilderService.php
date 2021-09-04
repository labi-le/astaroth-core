<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Astaroth\Auth\Configuration;
use Astaroth\VkUtils\Builder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class BuilderService
 * @package Astaroth\Services
 */
class BuilderService implements ServiceInterface
{
    public const SERVICE_ID = "builder";

    public function __invoke(ContainerBuilder $container)
    {
        $container
            ->register(self::SERVICE_ID, Builder::class)
            ->setLazy(true)
            ->addArgument($container->getParameter(Configuration::API_VERSION))
            ->addMethodCall("setDefaultToken", [$container->getParameter(Configuration::ACCESS_TOKEN)]);
    }
}