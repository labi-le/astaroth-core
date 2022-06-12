<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Enums\MessageValidation;
use Astaroth\TextMatcher;
use Attribute;
use function mb_strtolower;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class Message implements AttributeValidatorInterface, AttributeMethodInterface
{
    private string $haystack = "";

    public function __construct
    (
        private readonly string $message,
        private readonly MessageValidation $validation = MessageValidation::STRICT)
    {
    }

    public function validate(): bool
    {
        return (new TextMatcher(
            $this->message,
            mb_strtolower($this->haystack),
            $this->validation->value
        ))->compare();
    }

    /**
     * @param mixed $haystack
     * @return Message
     */
    public function setHaystack(mixed $haystack): Message
    {
        if ($haystack instanceof MessageNew) {
            $this->haystack = $haystack->getText();
        }

        return $this;
    }
}