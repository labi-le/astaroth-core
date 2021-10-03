<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the chat actions
 * only for MessageNew Event
 *
 * @see https://i.imgur.com/4YQWIZ4.png
 */
class Action implements AttributeValidatorInterface
{
    private ?object $haystack;

    public const CHAT_INVITE_USER = "chat_invite_user";
    public const CHAT_KICK_USER = "chat_kick_user";
    public const CHAT_PHOTO_UPDATE = "chat_photo_update";
    public const CHAT_PHOTO_REMOVE = "chat_photo_remove";
    public const CHAT_CREATE = "chat_create";
    public const CHAT_TITLE_UPDATE = "chat_title_update";
    public const CHAT_PIN_MESSAGE = "chat_pin_message";
    public const CHAT_UNPIN_MESSAGE = "chat_unpin_message";
    public const CHAT_INVITE_USER_BY_LINK = "chat_invite_user_by_link";

    /**
     * @param string $type accepts self constants
     * @param string[] $anyData example ["member_id" => 418618]
     *
     * @see https://i.imgur.com/S4vcS9w.png
     */
    public function __construct(private string $type, private array $anyData = [])
    {
    }

    public function validate(): bool
    {
        if (
            $this->haystack
            && $this->type === $this->haystack->type
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

    public function setHaystack($haystack): static
    {
        $this->haystack = $haystack;
        return $this;
    }
}