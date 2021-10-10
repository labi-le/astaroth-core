<?php

declare(strict_types=1);


namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;
use function array_key_exists;
use function is_array;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that determines the click on the button (payload)
 */
class Payload implements AttributeValidatorInterface
{

    public const KEY_EXIST = 0;
    public const STRICT = 1;
    public const CONTAINS = 2;

    private array|null $haystack;

    public function __construct(private array|string $payload_or_key, private int $validation = Payload::STRICT)
    {
    }

    public function validate(): bool
    {
        if ($this->haystack) {
            return match ($this->validation) {
                static::STRICT => $this->strictValidate($this->payload_or_key, $this->haystack),
                static::KEY_EXIST => $this->keyExistValidate($this->payload_or_key, $this->haystack),
                static::CONTAINS => $this->containsValidate($this->payload_or_key, $this->haystack),
            };
        }

        return false;
    }

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
            throw new \LogicException("Instead of a key, an array is specified for validation of the KEY_EXISTS type\nTo find the error, use the attribute data shown below\n" . print_r($this->payload_or_key, true));
        }
        return array_key_exists($payload, $haystack);
    }

    public function setHaystack($haystack): static
    {
        $this->haystack = $haystack;
        return $this;
    }
}