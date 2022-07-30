<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
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
final class Action implements AttributeValidatorInterface, AttributeMethodInterface
{
    private ?object $haystack;

    /**
     * @param ActionEnum $type accepts self constants
     * @param array $anyData example ["member_id" => 418618]
     *
     * @see https://i.imgur.com/S4vcS9w.png
     * @noinspection PhpPropertyCanBeReadonlyInspection
     */
    public function __construct(
        private ActionEnum $type,
        private array      $anyData = []
    ) {
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
     * @param mixed $haystack
     * @return Action
     */
    public function setHaystack(mixed $haystack): Action
    {
        if ($haystack instanceof MessageNew) {
            $this->haystack = $haystack->getAction();
        }

        return $this;
    }
}
