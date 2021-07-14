<?php

declare(strict_types=1);

namespace Astaroth\Services;

/**
 * Class PostService
 * @package Astaroth\Services
 */
class PostService
{
    public function __invoke(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $container
            ->register("post", \Astaroth\VkUtils\Post::class)
            ->setLazy(true)
            ->addArgument($container->getParameter("API_VERSION"))
            ->addMethodCall("setDefaultToken", [$container->getParameter("ACCESS_TOKEN")]);
    }
}