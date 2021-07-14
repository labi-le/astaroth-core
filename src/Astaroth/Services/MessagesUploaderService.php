<?php

declare(strict_types=1);

namespace Astaroth\Services;

/**
 * Class MessagesUploaderService
 * @package Astaroth\Services
 */
class MessagesUploaderService
{
    public function __invoke(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $container
            ->register(__CLASS__, \Astaroth\VkUtils\Uploading\MessagesUploader::class)
            ->setLazy(true)
            ->addArgument($container->getParameter("API_VERSION"))
            ->addMethodCall("setDefaultToken", [$container->getParameter("USER_ACCESS_TOKEN")]);
    }
}