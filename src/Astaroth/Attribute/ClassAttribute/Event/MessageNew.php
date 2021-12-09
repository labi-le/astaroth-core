<?php

declare(strict_types=1);

namespace Astaroth\Attribute\ClassAttribute\Event;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * AttributeOLD defining new message
 */
final class MessageNew implements AttributeValidatorInterface, AttributeMethodInterface
{
    private string $type = "";

    public function validate(): bool
    {
        return $this->type === "message_new";
    }

    /**
     * @param DataFetcher $haystack
     * @return $this
     */
    public function setHaystack($haystack): MessageNew
    {
        if (\method_exists($haystack, "getType")) {
            $this->type = $haystack->getType();
        }
        return $this;
    }
}