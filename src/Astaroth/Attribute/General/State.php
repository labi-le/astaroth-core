<?php

declare(strict_types=1);

namespace Astaroth\Attribute\General;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Enums\ConversationType;
use Astaroth\Support\Facades\Session;
use Attribute;
use LogicException;
use function is_object;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class State implements AttributeValidatorInterface, AttributeMethodInterface
{
    //reserved
    public const RESERVED_NAME = "__state";

    private null|MessageNew|MessageEvent $haystack;

    public function __construct
    (
        private readonly string           $state_name,
        private readonly ConversationType $member_type = ConversationType::PERSONAL
    )
    {
    }

    /**
     * @inheritDoc
     * @psalm-suppress RedundantCondition
     */
    public function validate(): bool
    {
        if (is_object($this->haystack)) {
            if ($this->haystack instanceof MessageNew) {
                $user_id = $this->haystack->getFromId();
            } elseif ($this->haystack instanceof MessageEvent) {
                $user_id = $this->haystack->getUserId();
            } else {
                throw new LogicException("Invalid message type");
            }

            $member_id = match ($this->member_type) {
                ConversationType::PERSONAL => $user_id,
                ConversationType::CHAT => (int)$this->haystack->getChatId(),
                ConversationType::ALL => $this->haystack->getPeerId(),
            };

            return (bool)(new Session($member_id, self::RESERVED_NAME))->get($this->state_name);
        }

        return false;
    }

    /**
     * @inheritDoc
     * @param mixed $haystack
     * @return State
     */
    public function setHaystack(mixed $haystack): State
    {
        if ($haystack instanceof MessageNew || $haystack instanceof MessageEvent) {
            $this->haystack = $haystack;
        }

        return $this;
    }
}