<?php
declare(strict_types=1);


namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Enums\Events;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * An attribute that determines whether a dialogue is a conversation, a personal message, or it doesn't matter.
 */
class Conversation implements AttributeValidatorInterface
{
    public const ALL = 6;
    public const CHAT = 12;
    public const PERSONAL_DIALOG = 24;

    /**
     * @var int[]
     */
    public array $member_id = [];

    private MessageNew|MessageEvent $haystack;

    /**
     * Conversation constructor.
     * @param int $type
     * @param int ...$member_id
     */
    public function __construct(public int $type = Conversation::ALL, int ...$member_id)
    {
        $this->member_id = $member_id;
    }

    public function validate(): bool
    {
        $concreteMethod = match ($this->haystack::class) {
            MessageNew::class => fn() => $this->haystack->getFromId(),
            MessageEvent::class => fn() => $this->haystack->getUserId(),
        };

        $type = match ($this->type) {
            static::PERSONAL_DIALOG => $this->haystack->getChatId() === null,
            static::ALL => (bool)$this->haystack->getPeerId(),
            static::CHAT => (bool)$this->haystack->getChatId()
        };

        $concreteId = match ($this->type) {
            static::PERSONAL_DIALOG => $concreteMethod(),
            static::ALL => $this->haystack->getPeerId(),
            static::CHAT => $this->haystack->getChatId()
        };

        if ($this->member_id === []) {
            return $type;
        }
        return in_array($concreteId, $this->member_id, true) && $type;
    }

    /**
     * @param DataFetcher $haystack
     * @return $this
     */
    public function setHaystack($haystack): static
    {
        $this->haystack = match ($haystack->getType()) {
            Events::MESSAGE_NEW => $haystack->messageNew(),
            Events::MESSAGE_EVENT => $haystack->messageEvent()
        };
        return $this;
    }
}