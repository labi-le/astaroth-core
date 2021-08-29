<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Event;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * Attribute defining new message
 */
class MessageEvent implements AttributeValidatorInterface
{
    private string $type;

    public function validate(): bool
    {
        return $this->type === "message_event";
    }

    public function setHaystack($haystack): static
    {
        $this->type = $haystack;
        return $this;
    }
}