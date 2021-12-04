<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Astaroth\Contracts\ContainerPlaceholderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DatabaseContainer
 * @package Astaroth\Containers
 */
final class DatabaseContainer implements ContainerPlaceholderInterface
{
    public const CONTAINER_ID = "db";

    /**
     * @throws ORMException|\Doctrine\ORM\ORMException
     */
    public function __invoke(ContainerBuilder $container, Configuration $configuration): void
    {
        $connection = [];
        if ($configuration->getDatabaseUrl()) {
            $connection["url"] = $configuration->getDatabaseUrl();
        } else if (
            $configuration->getDatabaseDriver() &&
            $configuration->getDatabaseHost() &&
            $configuration->getDatabaseName() &&
            $configuration->getDatabaseUser() &&
            $configuration->getDatabasePassword()
        ) {
            $connection["driver"] = $configuration->getDatabaseDriver();
            $connection["host"] = $configuration->getDatabaseHost();
            $connection["dbname"] = $configuration->getDatabaseName();
            $connection["user"] = $configuration->getDatabaseUser();
            $connection["password"] = $configuration->getDatabasePassword();

            if ($configuration->getDatabasePort()) {
                $connection["port"] = $configuration->getDatabasePort();
            }
        } else {
            return;
        }

        $config = Setup::createAnnotationMetadataConfiguration($configuration->getEntityPath(), $configuration->isDebug());

        $container
            ->set(self::CONTAINER_ID, EntityManager::create($connection, $config));
    }

}