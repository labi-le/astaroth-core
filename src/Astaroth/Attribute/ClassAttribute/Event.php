<?php

declare(strict_types=1);

namespace Astaroth\Attribute\ClassAttribute;

use Astaroth\Contracts\AttributeClassInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\Enums\Events;
use Attribute;
use function method_exists;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * AttributeOLD defining new message
 */
class Event implements AttributeValidatorInterface, AttributeClassInterface
{
    public function __construct(
        private readonly Events $event
    )
    {

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
     * @param $haystack
     * @return Event
     */
    public function setHaystack($haystack): Event
    {
        if (method_exists($haystack, "getType")) {
            $this->type = $haystack->getType();
        }

        return $this;
    }
}