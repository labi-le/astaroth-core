<?php

declare(strict_types=1);

namespace Astaroth\Enums;

enum MessageValidation: int
{
    case STRICT = 0;
    case CONTAINS = 1;
    case START_AS = 2;
    case END_AS = 3;
    case SIMILAR_TO = 4;
}
