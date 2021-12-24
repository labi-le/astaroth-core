<?php

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum Type: string implements EnvironmentInterface
{
    use EnvTrait;

    case CALLBACK = "callback";
    case LONGPOLL = "longpoll";
}