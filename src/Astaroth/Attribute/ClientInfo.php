<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message keyboard info
 * keyboard available on the current client, etc.
 */
class ClientInfo implements AttributeValidatorInterface
{
    public const TEXT = "text";
    public const VKPAY = "vkpay";
    public const OPEN_APP = "open_app";
    public const LOCATION = "location";
    public const OPEN_LINK = "open_link";
    public const CALLBACK = "callback";
    public const INTENT_SUBSCRIBE = "intent_subscribe";
    public const INTENT_UNSUBSCRIBE = "intent_unsubscribe";

    private object $client_info;

    /**
     * By default, a regular keyboard is installed that supports everything
     * @param array|string[] $button_actions
     * @param bool|null $keyboard
     * @param bool|null $inline_keyboard
     * @param bool|null $carousel
     * @param int $lang_id
     */
    public function __construct(
        private array $button_actions =
        [
            self::TEXT,
            self::VKPAY,
            self::OPEN_APP,
            self::LOCATION,
            self::OPEN_LINK,
            self::CALLBACK,
            self::INTENT_SUBSCRIBE,
            self::INTENT_UNSUBSCRIBE
        ],
        private bool $keyboard = true,
        private bool $inline_keyboard = true,
        private bool $carousel = true,
        private int  $lang_id = 0)
    {
    }

    public function validate(): bool
    {
        if (count(array_intersect_key(
                    $this->button_actions,
                    $this->client_info->button_actions
                )
            ) > 0 === false) {
            return false;
        }

        if (($this->client_info->keyboard === $this->keyboard) === false) {
            return false;
        }
        if (($this->client_info->inline_keyboard === $this->inline_keyboard) === false) {
            return false;
        }
        if (($this->client_info->carousel === $this->carousel) === false) {
            return false;
        }

        if (($this->client_info->lang_id === $this->lang_id) === false) {
            return false;
        }

        return true;
    }

    public function setHaystack($haystack): static
    {
        $this->client_info = $haystack;
        return $this;
    }
}