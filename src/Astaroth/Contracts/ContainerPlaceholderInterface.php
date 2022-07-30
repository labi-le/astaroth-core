<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ContainerPlaceholderInterface
{
    public function __invoke(ContainerBuilder $container, ConfigurationInterface $configuration): void;
}
