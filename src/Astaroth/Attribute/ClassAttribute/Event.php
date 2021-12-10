<?php

declare(strict_types=1);

namespace Astaroth\Attribute\ClassAttribute;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\Foundation\Enums\Events;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;
use function method_exists;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * AttributeOLD defining new message
 */
class Event implements AttributeValidatorInterface, AttributeMethodInterface
{
    public function __construct(
        #[ExpectedValues(flagsFromClass: Events::class)]
        private string $event
    )
    {

    }

    public function validate(): bool
    {
        return $this->event === $this->type;
    }


    /**
     * event that will be specified when validating the attribute
     * @var string
     */
    private string $type = "";

    /**
     *
     * @param $haystack
     * @return $this
     */
    public function setHaystack($haystack): Event
    {
        if (method_exists($haystack, "getType")) {
            $this->type = $haystack->getType();
        }

        return $this;
    }
}