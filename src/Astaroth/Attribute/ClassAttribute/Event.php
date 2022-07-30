<?php

declare(strict_types=1);

namespace Astaroth\Attribute\ClassAttribute;

use Astaroth\Contracts\AttributeClassInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\Enums\Events;
use Attribute;

use function is_object;
use function method_exists;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * AttributeOLD defining new message
 */
class Event implements AttributeValidatorInterface, AttributeClassInterface
{
    public function __construct(
        private readonly Events $event
    ) {
    }

    public function validate(): bool
    {
        return $this->event->value === $this->type;
    }


    /**
     * event that will be specified when validating the attribute
     * @var string
     */
    private string $type = "";

    /**
     *
     * @param mixed $haystack
     * @return static
     */
    public function setHaystack(mixed $haystack): Event
    {
        if (is_object($haystack) && method_exists($haystack, "getType")) {
            $this->type = (string)$haystack->getType();
        }

        return $this;
    }
}
