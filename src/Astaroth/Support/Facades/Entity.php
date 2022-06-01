<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Containers\DatabaseContainer;
use Astaroth\Foundation\FacadePlaceholder;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class Entity extends EntityManagerDecorator
{
    /**
     * @return EntityManagerInterface
     */
    private static function getContainer(): EntityManagerInterface
    {
        $container = FacadePlaceholder::getInstance()?->getContainer()->get(DatabaseContainer::CONTAINER_ID, ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if ($container instanceof EntityManagerInterface) {
            return $container;
        }

        throw new LogicException('Container not found');
    }

    public function __invoke(): Entity
    {
        return new Entity();
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function __construct()
    {
        parent::__construct(self::getContainer());
    }
}