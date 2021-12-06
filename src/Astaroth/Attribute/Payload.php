<?php

declare(strict_types=1);


namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeOptionalInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;
use LogicException;
use function array_key_exists;
use function is_array;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that determines the click on the button (payload)
 */
final class Payload implements AttributeValidatorInterface, AttributeOptionalInterface
{

    public const KEY_EXIST = 0;
    public const STRICT = 1;
    public const CONTAINS = 2;

    private ?array $haystack = null;

    public function __construct(
        private array|string $payload_or_key,
                             #[ExpectedValues(values: [self::STRICT, self::CONTAINS, self::KEY_EXIST])]
                             private int $validation = Payload::STRICT)
    {
    }

    public function validate(): bool
    {
        if ($this->haystack) {
            return match ($this->validation) {
                self::STRICT => $this->strictValidate($this->payload_or_key, $this->haystack),
                self::KEY_EXIST => $this->keyExistValidate($this->payload_or_key, $this->haystack),
                self::CONTAINS => $this->containsValidate($this->payload_or_key, $this->haystack),
            };
        }

        return false;
    }

    /**
     * @param array|string $payload
     * @param array $haystack
     * @return bool
     *
     * @psalm-suppress PossiblyInvalidArgument
     */
    private function containsValidate(array|string $payload, array $haystack): bool
    {
        return array_intersect($payload, $haystack) !== [];
    }

    private function strictValidate(array|string $payload, array $haystack): bool
    {
        return $payload === $haystack;
    }

    private function keyExistValidate(array|string $payload, array $haystack): bool
    {
        if (is_array($payload)) {
            throw new LogicException("Instead of a key, an array is specified for validation of the KEY_EXISTS type\nTo find the error, use the attribute data shown below\n" . print_r($this->payload_or_key, true));
        }
        return array_key_exists($payload, $haystack);
    }

    /**
     * @param $haystack
     * @return Payload
     */
    public function setHaystack($haystack): Payload
    {
        if ($haystack instanceof MessageNew || $haystack instanceof MessageEvent){
            $this->haystack = $haystack->getPayload();
        }

        return $this;
    }
}