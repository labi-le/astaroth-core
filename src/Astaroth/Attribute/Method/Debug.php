<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that always fires
 * It will be useful for dump
 */
final class Debug implements AttributeReturnInterface, AttributeValidatorInterface, AttributeMethodInterface
{
    private mixed $haystack;

    public function validate(): bool
    {
        return true;
    }

    /**
     * @param mixed $haystack
     * @return Debug
     */
    public function setHaystack(mixed $haystack): Debug
    {
        $this->haystack = $haystack;
        return $this;
    }

    public function return(): mixed
    {
        return $this->haystack;
    }
}
