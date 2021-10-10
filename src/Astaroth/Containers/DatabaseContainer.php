<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Astaroth\Contracts\ContainerPlaceholderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DatabaseContainer
 * @package Astaroth\Containers
 */
class DatabaseContainer implements ContainerPlaceholderInterface
{
    public const CONTAINER_ID = "db";

    /**
     * @throws ORMException
     */
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


        $config = Setup::createAnnotationMetadataConfiguration($configuration->getEntityPath(), $configuration->isDebug());

        $container
            ->set(self::CONTAINER_ID, EntityManager::create($connection, $config));
    }

}