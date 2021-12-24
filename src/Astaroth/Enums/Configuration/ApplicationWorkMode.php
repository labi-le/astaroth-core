<?php

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum ApplicationWorkMode implements EnvironmentInterface
{
    use EnvTrait;

    case PRODUCTION;
    case DEVELOPMENT;
}