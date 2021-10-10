<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Containers\DatabaseContainer;
use Astaroth\Foundation\FacadePlaceholder;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class Entity extends EntityManagerDecorator
{
    /**
     * @return ?EntityManagerInterface
     */
    private static function getContainer(): ?object
    {
        return FacadePlaceholder::getContainer()->get(DatabaseContainer::CONTAINER_ID, ContainerInterface::RUNTIME_EXCEPTION_ON_INVALID_REFERENCE);
    }

    public function __invoke(): Entity
    {
        return new Entity;
    }

    public function __construct()
    {
        parent::__construct(self::getContainer());
    }
}