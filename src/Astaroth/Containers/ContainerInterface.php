<?php

declare(strict_types=1);

namespace Astaroth\Containers;

use Astaroth\Auth\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ContainerInterface
{
    public function __invoke(ContainerBuilder $container, Configuration $configuration): void;
}