<?php

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum Database implements EnvironmentInterface
{
    use EnvTrait;

    case DATABASE_DRIVER;
    case DATABASE_NAME;
    case DATABASE_USER;
    case DATABASE_PASSWORD;
    case DATABASE_URL;
    case DATABASE_HOST;
    case DATABASE_PORT;

    case ENTITY_PATH;
}