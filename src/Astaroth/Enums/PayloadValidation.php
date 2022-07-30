<?php

declare(strict_types=1);

namespace Astaroth\Enums;

enum PayloadValidation: int
{
    case KEY_EXIST = 0;
    case STRICT = 1;
    case CONTAINS = 2;
}
