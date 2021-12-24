<?php

declare(strict_types=1);


namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Enums\PayloadValidation;
use Attribute;
use LogicException;
use function array_intersect;
use function array_key_exists;
use function is_array;
use function print_r;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that determines the click on the button (payload)
 */
final class Payload implements AttributeValidatorInterface, AttributeMethodInterface
{


    private ?array $haystack = null;

    public function __construct(
        private array|string      $payload_or_key,
        private PayloadValidation $validation = PayloadValidation::STRICT)
    {
    }

    public function validate(): bool
    {
        if ($this->haystack) {
            return match ($this->validation) {
                PayloadValidation::STRICT => $this->strictValidate($this->payload_or_key, $this->haystack),
                PayloadValidation::KEY_EXIST => $this->keyExistValidate($this->payload_or_key, $this->haystack),
                PayloadValidation::CONTAINS => $this->containsValidate($this->payload_or_key, $this->haystack),
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
        if ($haystack instanceof MessageNew || $haystack instanceof MessageEvent) {
            $this->haystack = $haystack->getPayload();
        }

        return $this;
    }
}