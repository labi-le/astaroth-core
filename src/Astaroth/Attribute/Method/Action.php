<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeClassInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Enums\ActionEnum;
use Attribute;
use function current;
use function key;
use function property_exists;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the chat actions
 * only for MessageNew Event
 *
 * @see https://i.imgur.com/4YQWIZ4.png
 */
final class Action implements AttributeValidatorInterface, AttributeClassInterface
{
    private readonly object $haystack;

    /**
     * @param ActionEnum $type accepts self constants
     * @param array $anyData example ["member_id" => 418618]
     *
     * @see https://i.imgur.com/S4vcS9w.png
     */
    public function __construct(
        private readonly ActionEnum $type,
        private array      $anyData = []
    )
    {
    }

    public function validate(): bool
    {
        //if haystack is object and type given in the attribute matches the type haystack
        if (
            $this->haystack
            && $this->type->value === $this->haystack->type
        ) {
            if ($this->anyData === []) {
                return true;
            }

            $key = key($this->anyData);

            return property_exists($this->haystack, $key)
                && $this->haystack->$key === current($this->anyData);
        }

        return false;
    }

    /**
     * @param $haystack
     * @return Action
     */
    public function setHaystack($haystack): Action
    {
        if ($haystack instanceof MessageNew) {
            $this->haystack = $haystack->getAction();
        }

        return $this;
    }
}