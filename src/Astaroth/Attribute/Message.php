<?php

namespace Astaroth\Attribute;

use Astaroth\TextMatcher;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
class Message
{

    public function __construct(public string $message, public int $validation = TextMatcher::)
    {
    }
}