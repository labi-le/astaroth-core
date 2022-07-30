<?php

declare(strict_types=1);

namespace Astaroth\Attribute\ClassAttribute;

use Astaroth\Contracts\AttributeClassInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Enums\ConversationType;
use Attribute;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

use function in_array;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * An attribute that determines whether a dialogue is a conversation, a personal message, or it doesn't matter.
 */
final class Conversation implements AttributeValidatorInterface, AttributeClassInterface
{
    /**
     * @var int[]
     */
    public array $member_id = [];

    private null|MessageNew|MessageEvent $haystack = null;

    /**
     * Conversation constructor.
     * @param ConversationType $type
     * @param int ...$member_id
     */
    public function __construct(
        private readonly ConversationType $type = ConversationType::ALL,
        int ...$member_id
    ) {
        $this->member_id = $member_id;
    }

    public function validate(): bool
    {
        if ($this->haystack) {
            $validate = match ($this->type) {
                ConversationType::PERSONAL => $this->personalDialogValidate($this->haystack),
                ConversationType::ALL => $this->allDialogValidate($this->haystack),
                ConversationType::CHAT => $this->chatValidate($this->haystack)
            };

            //if the ID array is not specified in the attribute, then we check if the type matches
            if ($this->member_id === []) {
                return (bool)$validate["type"];
            }

            //condition opposite to above condition
            return in_array($validate["id"], $this->member_id, true) && $validate["type"];
        }

        return false;
    }

    #[ArrayShape(["type" => "bool", "id" => "int"])]
    private function personalDialogValidate(MessageNew|MessageEvent $data): array
    {
        return
            [
                //if the chat id === null then this is clearly a personal conversation
                "type" => $data->getChatId() === null,
                "id" =>
                /**
                 * we find out the user's ID based on the fact that different events allow you to do this in different ways
                 */
                    match ($data::class) {
                        MessageNew::class => $data->getFromId(),
                        MessageEvent::class => $data->getUserId(),
                    }
            ];
    }

    #[Pure] #[ArrayShape(["type" => "bool", "id" => "int"])]
    private function allDialogValidate(MessageNew|MessageEvent $data): array
    {
        return
            [
                "type" => (bool)$data->getPeerId(),
                "id" => $data->getPeerId()
            ];
    }

    #[Pure] #[ArrayShape(["type" => "bool", "id" => "int"])]
    private function chatValidate(MessageNew|MessageEvent $data): array
    {
        return
            [
                "type" => (bool)$data->getChatId(),
                "id" => $data->getChatId()
            ];
    }

    /**
     * @param mixed $haystack
     * @return Conversation
     */
    public function setHaystack(mixed $haystack): Conversation
    {
        if ($haystack instanceof MessageEvent || $haystack instanceof MessageNew) {
            $this->haystack = $haystack;
        }

        return $this;
    }
}
