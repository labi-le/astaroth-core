<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Astaroth\Auth\Configuration;
use Astaroth\Auth\ParameterMissingException;
use Astaroth\VkUtils\Builder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class BuilderService
 * @package Astaroth\Services
 */
class BuilderService implements ServiceInterface
{
    public const SERVICE_ID = "builder";

    /**
     * @throws ParameterMissingException
     */
    public function __invoke(ContainerBuilder $container, Configuration $configuration)
    {
        $container
            ->register(self::SERVICE_ID, Builder::class)
            ->setLazy(true)
            ->addArgument($configuration->getApiVersion())
            ->addMethodCall("setDefaultToken", [$configuration->getAccessToken()]);
    }
}