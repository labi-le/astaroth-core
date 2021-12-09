<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\TextMatcher;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class Message implements AttributeValidatorInterface, AttributeMethodInterface
{
    private string $haystack = "";

    public const STRICT = 0;
    public const CONTAINS = 1;
    public const START_AS = 2;
    public const END_AS = 3;
    public const SIMILAR_TO = 4;

    public function __construct
    (
        private string $message,
                       #[ExpectedValues(values: [self::STRICT, self::CONTAINS, self::START_AS, self::END_AS, self::SIMILAR_TO]
                       )]
                       private int $validation = Message::STRICT)
    {
    }

    public function validate(): bool
    {
        return (new TextMatcher(
            $this->message,
            \mb_strtolower($this->haystack),
            $this->validation
        ))->compare();
    }

    /**
     * @param $haystack
     * @return Message
     */
    public function setHaystack($haystack): Message
    {
        if ($haystack instanceof MessageNew) {
            $this->haystack = $haystack->getText();
        }

        return $this;
    }
}