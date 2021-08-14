<?php


namespace Astaroth\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that determines the click on the button (payload)
 */
class Payload
{
    public function __construct(public array $payload){}
}