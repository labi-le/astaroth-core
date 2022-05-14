<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Foundation\Queue as _Queue;

/**
 * @deprecated
 */
final class Queue extends _Queue
{
    public function __construct(int $id, string $type, callable ...$queue)
    {
        parent::__construct(new Session($id, $type), ...$queue);
    }
}