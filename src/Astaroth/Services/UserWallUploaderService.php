<?php

declare(strict_types=1);

namespace Astaroth\Services;

/**
 * Class UserWallUploaderService
 * @package Astaroth\Services
 */
class UserWallUploaderService
{
    public function __invoke(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $container
            ->register(__CLASS__, \Astaroth\VkUtils\Uploading\WallUploader::class)
            ->setLazy(true)
            ->addArgument($container->getParameter("API_VERSION"))
            ->addMethodCall("setDefaultToken", [$container->getParameter("USER_ACCESS_TOKEN")]);
    }
}