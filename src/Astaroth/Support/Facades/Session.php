<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\Foundation\Session as _Session;

class Session
{
    public static function set(int $id, string $type): _Session
    {
        return new _Session($id, $type, FacadePlaceholder::getConfiguration()->getCachePath());
    }
}