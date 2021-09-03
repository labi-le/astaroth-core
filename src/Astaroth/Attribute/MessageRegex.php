<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
class MessageRegex implements AttributeValidatorInterface
{
    private string $haystack;

    public function __construct(private string $pattern)
    {
    }

    public function validate(): bool
    {
        return (bool)preg_match($this->pattern, $this->haystack);
    }

    public function setHaystack($haystack): static
    {
        $this->haystack = $haystack;
        return $this;
    }
}