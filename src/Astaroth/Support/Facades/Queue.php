<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Foundation\Queue as _Queue;

class Queue
{
    public static function create(int $id, string $type, callable ...$queue)
    {
        return new _Queue(Session::set($id, $type), ...$queue);
    }
}