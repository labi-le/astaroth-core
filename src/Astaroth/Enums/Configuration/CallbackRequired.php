<?php
declare(strict_types=1);

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum CallbackRequired implements EnvironmentInterface
{
    use EnvTrait;

    case CONFIRMATION_KEY;
    case SECRET_KEY;
    case HANDLE_REPEATED_REQUESTS;
}