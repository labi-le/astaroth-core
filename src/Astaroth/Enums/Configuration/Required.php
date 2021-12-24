<?php

namespace Astaroth\Enums\Configuration;

use Astaroth\Contracts\EnvironmentInterface;

enum Required implements EnvironmentInterface
{
    use EnvTrait;

    case TYPE;

    case ACCESS_TOKEN;
    case API_VERSION;
    case APP_NAMESPACE;

    public static function toArray(): array
    {
        $arr = [];
        foreach (self::cases() as $case) {
            $arr[] = $case->name;
        }

        return $arr;
    }
}