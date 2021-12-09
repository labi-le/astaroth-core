<?php

declare(strict_types=1);

namespace Astaroth\Attribute\ClassAttribute\Event;

use Astaroth\Contracts\AttributeRequiredInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * AttributeOLD defining new message
 */
final class MessageEvent implements AttributeValidatorInterface, AttributeRequiredInterface
{
    private string $type = "";

    public function validate(): bool
    {
        return $this->type === "message_event";
    }

    /**
     * @param DataFetcher $haystack
     * @return MessageEvent
     */
    public function setHaystack($haystack): MessageEvent
    {
        if (method_exists($haystack, "getType")) {
            $this->type = $haystack->getType();
        }

        return $this;
    }
}