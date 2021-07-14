<?php

declare(strict_types=1);

namespace Astaroth\Services;

/**
 * Class WallUploaderService
 * @package Astaroth\Services
 */
class WallUploaderService
{
    public function __invoke(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $container
            ->register("wall.uploader", \Astaroth\VkUtils\Uploading\WallUploader::class)
            ->setLazy(true)
            ->addArgument($container->getParameter("API_VERSION"))
            ->addMethodCall("setDefaultToken", [$container->getParameter("ACCESS_TOKEN")]);
    }
}