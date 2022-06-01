<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Foundation\FacadePlaceholder;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function sprintf;

abstract class AbstractFacade
{
    abstract protected static function getServiceName(): string;

    protected static function getContainerService(): object
    {
        $service = FacadePlaceholder::getInstance()?->getContainer()->get(static::getServiceName(), ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if ($service === null) {
            throw new LogicException(sprintf('Service %s not found', static::getServiceName()));
        }

        return $service;
    }
}