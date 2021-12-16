<?php

declare(strict_types=1);

namespace Astaroth\Attribute\ClassAttribute;

use Astaroth\Contracts\AttributeClassInterface;
use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
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
final class Conversation implements AttributeValidatorInterface, AttributeClassInterface
{
    public const ALL = 6;
    public const CHAT = 12;
    public const PERSONAL_DIALOG = 24;

    /**
     * @var int[]
     */
    public array $member_id = [];

    private null|MessageNew|MessageEvent $haystack = null;

    /**
     * Conversation constructor.
     * @param int $type
     * @param int ...$member_id
     */
    public function __construct(
        #[ExpectedValues(values: [self::ALL, self::PERSONAL_DIALOG, self::CHAT])]
        public int $type = Conversation::ALL,
        int ...$member_id
    )
    {
        $this->member_id = $member_id;
    }

    #[Pure] public function validate(): bool
    {
        if ($this->haystack) {

            $validate = match ($this->type) {
                self::PERSONAL_DIALOG => $this->personalDialogValidate($this->haystack),
                self::ALL => $this->allDialogValidate($this->haystack),
                self::CHAT => $this->chatValidate($this->haystack)
            };

            //if the ID array is not specified in the attribute, then we check if the type matches
            if ($this->member_id === []) {
                return $validate["type"];
            }

            //condition opposite to above condition
            return in_array($validate["id"], $this->member_id, true) && $validate["type"];
        }

        return false;

    }

    #[Pure] #[ArrayShape(["type" => "bool", "id" => "int"])]
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
     * @param $haystack
     * @return Conversation
     */
    public function setHaystack($haystack): Conversation
    {
        if ($haystack instanceof MessageEvent || $haystack instanceof MessageNew) {
            $this->haystack = $haystack;
        }

        return $this;
    }
}