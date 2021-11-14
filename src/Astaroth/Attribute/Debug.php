<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\TextMatcher;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that always fires
 * It will be useful for dump
 */
final class Debug implements AttributeReturnInterface
{
    private $haystack;

    public function __construct()
    {
    }

    public function validate(): bool
    {
        return true;
    }

    public function setHaystack($haystack): Debug
    {
        $this->haystack = $haystack;
        return $this;
    }

    public function getResult(): mixed
    {
        return $this->haystack;
    }
}