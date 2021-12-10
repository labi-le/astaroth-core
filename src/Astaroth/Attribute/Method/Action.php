<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeClassInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;
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
    private ?object $haystack = null;

    public const CHAT_INVITE_USER = "chat_invite_user";
    public const CHAT_KICK_USER = "chat_kick_user";
    public const CHAT_PHOTO_UPDATE = "chat_photo_update";
    public const CHAT_PHOTO_REMOVE = "chat_photo_remove";
    public const CHAT_CREATE = "chat_create";
    public const CHAT_TITLE_UPDATE = "chat_title_update";
    public const CHAT_PIN_MESSAGE = "chat_pin_message";
    public const CHAT_UNPIN_MESSAGE = "chat_unpin_message";
    public const CHAT_INVITE_USER_BY_LINK = "chat_invite_user_by_link";
    public const CONVERSATION_STYLE_UPDATE = "conversation_style_update";

    /**
     * @param string $type accepts self constants
     * @param array $anyData example ["member_id" => 418618]
     *
     * @see https://i.imgur.com/S4vcS9w.png
     */
    public function __construct(
        #[ExpectedValues(values: [
            self::CHAT_PHOTO_UPDATE,
            self::CHAT_PIN_MESSAGE,
            self::CHAT_UNPIN_MESSAGE,
            self::CHAT_PHOTO_REMOVE,
            self::CHAT_INVITE_USER_BY_LINK,
            self::CHAT_INVITE_USER,
            self::CHAT_KICK_USER,
            self::CHAT_CREATE,
            self::CHAT_TITLE_UPDATE,
            self::CONVERSATION_STYLE_UPDATE,
        ]
        )]
        private string $type,
        private array $anyData = []
    )
    {
    }

    public function validate(): bool
    {
        //if haystack is object and type given in the attribute matches the type haystack
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