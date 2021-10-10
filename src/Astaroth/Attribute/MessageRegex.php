<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;
use JetBrains\PhpStorm\Language;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class MessageRegex implements AttributeValidatorInterface
{
    private string $haystack;
    private string $pattern;

    public function __construct(#[Language("PhpRegExp")] string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate(): bool
    {
        try {
            return (bool)preg_match($this->pattern, $this->haystack);
        } catch (\Exception) {
            return false;
        }
    }

    public function setHaystack($haystack): MessageRegex
    {
        $this->haystack = $haystack;
        return $this;
    }
}