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
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress MoreSpecificReturnType
     */
    private static function getContainer(): ?object
    {
        return FacadePlaceholder::getInstance()->getContainer()->get(DatabaseContainer::CONTAINER_ID, ContainerInterface::RUNTIME_EXCEPTION_ON_INVALID_REFERENCE);
    }

    public function __invoke(): Entity
    {
        return new Entity;
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function __construct()
    {
        parent::__construct(self::getContainer());
    }
}