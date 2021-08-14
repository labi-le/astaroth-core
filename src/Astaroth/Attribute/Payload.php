<?php

declare(strict_types=1);


namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that determines the click on the button (payload)
 */
class Payload implements AttributeValidatorInterface
{

    public const KEY_EXISTS = 0;
    public const STRICT = 1;
    public const CONTAINS = 2;
    public const CONTAINS_STRICT_TYPES = 3;

    private mixed $haystack;

    public function __construct(private array $payload, private int $validation = Payload::STRICT)
    {
    }

    /**
     * @throws NotImplementedHaystackException
     */
    public function validate(): bool
    {
        isset($this->haystack) ?: throw new NotImplementedHaystackException("No haystack specified for " . __CLASS__ . " Attribute");

        $casted_haystack = is_string($this->haystack) ? $this->haystack : "";
        $casted_payload = @json_decode($casted_haystack, true);
        if ($casted_payload) {
            return match ($this->validation) {
                static::STRICT => $this->payload === $casted_payload,
                static::KEY_EXISTS => array_key_exists(key($casted_payload), $this->payload),
                static::CONTAINS => in_array($this->payload, $casted_payload, false),
                static::CONTAINS_STRICT_TYPES => in_array($this->payload, $casted_payload, true),
            };
        }

        return false;
    }

    public function setHaystack($haystack): static
    {
        $this->haystack = $haystack;
        return $this;
    }
}