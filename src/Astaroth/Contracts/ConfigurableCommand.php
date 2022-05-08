<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

/**
 * Ability to pass configuration to cli command
 */
interface ConfigurableCommand
{
    public function __construct(ConfigurationInterface $configuration);
}