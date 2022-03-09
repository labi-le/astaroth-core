<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\Foundation\Session as _Session;

final class Session extends _Session
{
    public function __construct(int $id, string $type)
    {
        parent::__construct($id, $type, FacadePlaceholder::getInstance()->getConfiguration()->getCachePath());
    }
}