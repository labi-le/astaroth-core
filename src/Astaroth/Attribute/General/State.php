<?php

declare(strict_types=1);

namespace Astaroth\Attribute\General;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Support\Facades\Session;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class State implements AttributeValidatorInterface, AttributeMethodInterface
{
    public const USER = 0;
    public const CHAT = 1;
    public const PEER = 3;

    //reserved
    public const RESERVED_NAME = "__state";

    private null|MessageNew|MessageEvent $haystack;

    public function __construct
    (
        private string $state_name,
                       #[ExpectedValues(values: [self::CHAT, self::PEER, self::USER])]
                       private int $member_type = State::USER
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function validate(): bool
    {
        if ($this->haystack) {
            /** @psalm-suppress PossiblyUndefinedMethod */
            $user_id = match ($this->haystack::class) {
                MessageNew::class => fn() => $this->haystack?->getFromId(),
                MessageEvent::class => fn() => $this->haystack?->getUserId(),
            };

            $member_id = match ($this->member_type) {
                self::USER => (int)$user_id(),
                self::CHAT => (int)$this->haystack->getChatId(),
                self::PEER => $this->haystack->getPeerId(),
            };

            return (bool)(new Session($member_id, self::RESERVED_NAME))->get($this->state_name);
        }

        return false;
    }

    /**
     * @inheritDoc
     * @param $haystack
     * @return State
     */
    public function setHaystack($haystack): State
    {
        if ($haystack instanceof MessageNew || $haystack instanceof MessageEvent) {
            $this->haystack = $haystack;
        }

        return $this;
    }
}