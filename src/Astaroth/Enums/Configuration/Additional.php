<?php

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum Additional implements EnvironmentInterface
{
    use EnvTrait;

    case DEBUG;
    case CACHE_PATH;
}