<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Astaroth\Auth\Configuration;
use Astaroth\Foundation\Application;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DatabaseService implements ServiceInterface
{
    public const SERVICE_ID = "db";

    public function __invoke(ContainerBuilder $container)
    {
        [$dirty_entity_namespaces, $isDev, $driver, $host, $database_name, $database_user, $database_password] =
            [
                $container->getParameter(Configuration::ENTITY_NAMESPACE),
                $container->getParameter(Configuration::TYPE) === Application::DEV,
                $container->getParameter(Configuration::DATABASE_DRIVER),
                $container->getParameter(Configuration::DATABASE_HOST),
                $container->getParameter(Configuration::DATABASE_USER),
                $container->getParameter(Configuration::DATABASE_PASSWORD),
            ];

        if (
            empty($driver) ||
            empty($host) ||
            empty($database_name) ||
            empty($database_user) ||
            empty($database_password)
        ) {
            return;
        }

        $connection =
            [
                "driver" => $driver,

                "host" => $host,
                "dbname" => $database_user,
                "user" => $database_user,
                "password" => $database_password
            ];


        $entity_namespace = array_map('trim', explode(',', (string)$dirty_entity_namespaces));

        $config = Setup::createAnnotationMetadataConfiguration([...$entity_namespace], $isDev);
        $entityManager = EntityManager::create($connection, $config);

        $container
            ->register(self::SERVICE_ID, \Doctrine\ORM\Configuration::class)
            ->setLazy(true)
            ->addArgument($container->getParameter(Configuration::API_VERSION))
            ->addMethodCall("", [$container->getParameter(Configuration::ACCESS_TOKEN)]);
    }
}