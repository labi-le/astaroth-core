<?php

declare(strict_types=1);

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum Required implements EnvironmentInterface
{
    use EnvTrait;

    case TYPE;

    case ACCESS_TOKEN;
    case API_VERSION;
    case APP_NAMESPACE;
}
