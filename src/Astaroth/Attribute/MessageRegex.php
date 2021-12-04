<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use ArrayAccess;
use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;
use Exception;
use JetBrains\PhpStorm\Language;
use ReturnTypeWillChange;
use function count;
use function is_null;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class MessageRegex implements AttributeValidatorInterface, ArrayAccess
{
    private string $haystack;
    private string $pattern;

    private array $matches = [];

    public function __construct(#[Language("RegExp")] string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate(): bool
    {
        if (count($matches = $this->match()) > 0) {
            $this->matches = $matches;
            return true;
        }
        return false;
    }

    public function setHaystack($haystack): MessageRegex
    {
        $this->haystack = $haystack;
        return $this;
    }

    /**
     * @return string[]
     */
    private function match(): array
    {
        /** @var string[] $matches */
        $matches = [];
        @preg_match($this->pattern, $this->haystack, $matches);

        return $matches;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->matches[$offset]);
    }

    #[ReturnTypeWillChange] public function offsetGet($offset)
    {
        return $this->matches[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->matches[] = $value;
        } else {
            $this->matches[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->matches[$offset]);
    }
}