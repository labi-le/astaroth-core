<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Astaroth\Auth\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceInterface
{
    public function __invoke(ContainerBuilder $container, Configuration $configuration): void;
}