<?php

declare(strict_types=1);

namespace Astaroth\Services;

/**
 * Class MessageService
 * @package Astaroth\Services
 */
class MessageService
{
    public function __invoke(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $container
            ->register("message",\Astaroth\VkUtils\Message::class)
            ->setLazy(true)
            ->addArgument($container->getParameter("API_VERSION"))
            ->addMethodCall("setDefaultToken", [$container->getParameter("ACCESS_TOKEN")]);
    }
}