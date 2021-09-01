<?php

declare(strict_types=1);

namespace Astaroth\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceInterface
{
    public function __invoke(ContainerBuilder $container);
}