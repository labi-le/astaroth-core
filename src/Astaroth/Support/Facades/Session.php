<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Foundation\Session as _Session;

use function sys_get_temp_dir;

final class Session extends _Session
{
    public function __construct(int $id, string $type)
    {
        parent::__construct($id, $type, sys_get_temp_dir());
    }
}
