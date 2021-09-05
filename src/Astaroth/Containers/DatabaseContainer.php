<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DatabaseContainer implements ContainerInterface
{
    public const CONTAINER_ID = "db";

    public function __invoke(ContainerBuilder $container, Configuration $configuration): void
    {
        if (
            empty($configuration->getDatabaseDriver()) ||
            empty($configuration->getDatabaseHost()) ||
            empty($configuration->getDatabaseName()) ||
            empty($configuration->getDatabaseUser()) ||
            empty($configuration->getDatabasePassword())
        ) {
            return;
        }

        $connection =
            [
                "driver" => $configuration->getDatabaseDriver(),
                "host" => $configuration->getDatabaseHost(),
                "dbname" => $configuration->getDatabaseName(),
                "user" => $configuration->getDatabaseUser(),
                "password" => $configuration->getDatabasePassword()
            ];


        $config = Setup::createAnnotationMetadataConfiguration($configuration->getEntityNamespace(), $configuration->isDebug());

        $container
            ->register(self::CONTAINER_ID, EntityManager::class)
            ->setLazy(true)
            ->addMethodCall("create", [$connection, $config]);
    }
}