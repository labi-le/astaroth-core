<?php

declare(strict_types=1);

namespace Astaroth\Services;

/**
 * Class UserClientService
 * @package Astaroth\Services
 */
class UserClientService
{
    public function __invoke(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $container
            ->register(__CLASS__,\Astaroth\VkUtils\Client::class)
            ->setLazy(true)
            ->addArgument($container->getParameter("API_VERSION"))
            ->addMethodCall("setDefaultToken", [$container->getParameter("USER_ACCESS_TOKEN")]);
    }
}