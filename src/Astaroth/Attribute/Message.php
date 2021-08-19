<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\TextMatcher;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
class Message implements AttributeValidatorInterface
{
    private string $haystack;

    public const STRICT = 0;
    public const CONTAINS = 1;
    public const START_AS = 2;
    public const END_AS = 3;
    public const SIMILAR_TO = 4;

    public function __construct(private string $message, private int $validation = Message::STRICT)
    {
    }

    public function validate(): bool
    {
        return (new TextMatcher(
            $this->message,
            mb_strtolower($this->haystack),
            $this->validation
        ))->compare();
    }

    public function setHaystack($haystack): static
    {
        $this->haystack = $haystack;
        return $this;
    }
}