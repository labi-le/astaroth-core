<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use ArrayAccess;
use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Attribute;
use JetBrains\PhpStorm\Language;

use function count;
use function preg_match;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class MessageRegex implements AttributeValidatorInterface, ArrayAccess, AttributeMethodInterface, AttributeReturnInterface
{
    private string $haystack = "";
    private readonly string $pattern;

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

    /**
     * @param mixed $haystack
     * @return MessageRegex
     */
    public function setHaystack(mixed $haystack): MessageRegex
    {
        if ($haystack instanceof MessageNew) {
            $this->haystack = $haystack->getText();
        }

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

    /**
     * @param $offset
     * @return bool
     * @psalm-suppress MixedArrayOffset, MissingParamType
     */
    public function offsetExists($offset): bool
    {
        return isset($this->matches[$offset]);
    }

    /**
     * @param $offset
     * @return mixed
     * @psalm-suppress MixedArrayOffset, MissingParamType
     */
    public function offsetGet($offset): mixed
    {
        return $this->matches[$offset];
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     * @psalm-suppress MixedArrayOffset, MissingParamType
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->matches[] = $value;
        } else {
            $this->matches[$offset] = $value;
        }
    }

    /**
     * @param $offset
     * @return void
     * @psalm-suppress MixedArrayOffset, MissingParamType
     */
    public function offsetUnset($offset): void
    {
        unset($this->matches[$offset]);
    }

    public function return(): array
    {
        return $this->matches;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }
}
