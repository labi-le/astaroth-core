<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Support\Facades\Session;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class State implements AttributeValidatorInterface
{
    public const USER = 0;
    public const CHAT = 1;
    public const PEER = 3;

    //reserved
    private const STATE = "_state";

    private MessageNew|MessageEvent $haystack;

    public function __construct(private string $state_name, private int $member_type = State::USER)
    {
    }

    /**
     * @inheritDoc
     */
    public function validate(): bool
    {
        $user_id = match ($this->haystack::class) {
            MessageNew::class => fn() => $this->haystack->getFromId(),
            MessageEvent::class => fn() => $this->haystack->getUserId(),
        };

        $member_id = match ($this->member_type) {
            self::USER => $user_id(),
            self::CHAT => $this->haystack->getChatId(),
            self::PEER => $this->haystack->getPeerId(),
        };

        return (bool)(new Session($member_id, self::STATE))->get($this->state_name);
    }

    /**
     * @inheritDoc
     */
    public function setHaystack($haystack)
    {
        $this->haystack = $haystack;
        return $this;
    }
}