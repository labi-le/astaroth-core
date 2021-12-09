<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeRequiredInterface;
use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that always fires
 * It will be useful for dump
 */
final class Debug implements AttributeReturnInterface, AttributeValidatorInterface, AttributeRequiredInterface
{
    private mixed $haystack;

    public function validate(): bool
    {
        return true;
    }

    /**
     * @param $haystack
     * @return Debug
     */
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