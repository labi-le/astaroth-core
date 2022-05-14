<?php
declare(strict_types=1);

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum LongpollRequired implements EnvironmentInterface
{
    use EnvTrait;

    case COUNT_PARALLEL_OPERATIONS;
}