<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Enums\Events;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Attribute;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use function in_array;

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
    public function __construct(
        #[ExpectedValues(values: [static::ALL, static::PERSONAL_DIALOG, static::CHAT])]
        public int $type = Conversation::ALL,
        int ...$member_id
    )
    {
        $this->member_id = $member_id;
    }

    public function validate(): bool
    {

        $validate = match ($this->type) {
            static::PERSONAL_DIALOG => $this->personalDialogValidate($this->haystack),
            static::ALL => $this->allDialogValidate($this->haystack),
            static::CHAT => $this->chatValidate($this->haystack)
        };

        //if the ID array is not specified in the attribute, then we check if the type matches
        if ($this->member_id === []) {
            return $validate["type"];
        }

        //condition opposite to above condition
        return in_array($validate["id"], $this->member_id, true) && $validate["type"];
    }

    #[Pure] #[ArrayShape(["type" => "bool", "id" => "int"])]
    private function personalDialogValidate(MessageNew|MessageEvent $data): array
    {
        return
            [
                "type" => $this->haystack->getChatId() === null,
                "id" =>
                /**
                 * we find out the user's ID based on the fact that different events allow you to do this in different ways
                 * since it is a closure it will be executed below
                 */
                    match ($data::class) {
                        MessageNew::class => $this->haystack->getFromId(),
                        MessageEvent::class => $this->haystack->getUserId(),
                    }
            ];
    }

    #[Pure] #[ArrayShape(["type" => "bool", "id" => "int"])]
    private function allDialogValidate(MessageNew|MessageEvent $data): array
    {
        return
            [
                "type" => (bool)$this->haystack->getPeerId(),
                "id" => $this->haystack->getPeerId()
            ];
    }

    #[Pure] #[ArrayShape(["type" => "bool", "id" => "int"])]
    private function chatValidate(MessageNew|MessageEvent $data): array
    {
        return
            [
                "type" => (bool)$this->haystack->getChatId(),
                "id" => $this->haystack->getChatId()
            ];
    }

    /**
     * @param DataFetcher $haystack
     * @return $this
     */
    public function setHaystack($haystack): static
    {
        $this->haystack = match ($haystack->getType()) {
            Events::MESSAGE_NEW => $haystack->messageNew(),
            Events::MESSAGE_EVENT => $haystack->messageEvent(),
        };
        return $this;
    }
}