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

    public function __construct(private string $message, private int $validation = TextMatcher::STRICT)
    {
    }

    /**
     * @throws NotImplementedHaystackException
     */
    public function validate(): bool
    {
        isset($this->haystack) ?: throw new NotImplementedHaystackException("No haystack specified for " . __CLASS__ . " Attribute");

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