<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Enums\ClientInfoEnum;
use Attribute;
use function array_intersect_key;
use function array_map;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message keyboard info
 * keyboard available on the current client, etc.
 */
final class ClientInfo implements AttributeValidatorInterface, AttributeMethodInterface
{
    private readonly object $client_info;
    private readonly array $button_actions;

    /**
     * By default, a regular keyboard is installed that supports everything
     * @param ClientInfoEnum[] $button_actions
     * @param bool $keyboard
     * @param bool $inline_keyboard
     * @param bool $carousel
     * @param int $lang_id
     */
    public function __construct(
        array        $button_actions =
        [
            ClientInfoEnum::TEXT,
            ClientInfoEnum::VKPAY,
            ClientInfoEnum::OPEN_APP,
            ClientInfoEnum::LOCATION,
            ClientInfoEnum::OPEN_LINK,
            ClientInfoEnum::CALLBACK,
            ClientInfoEnum::INTENT_SUBSCRIBE,
            ClientInfoEnum::INTENT_UNSUBSCRIBE
        ],
        private bool $keyboard = true,
        private bool $inline_keyboard = true,
        private bool $carousel = true,
        private int  $lang_id = 0,
    )
    {
        $this->button_actions = array_map(static fn(ClientInfoEnum $enum) => $enum->value, $button_actions);
    }

    public function validate(): bool
    {
        if ($this->client_info) {
            if (($this->button_actions !== []) && array_intersect_key(
                    $this->button_actions,
                    $this->client_info->button_actions) === []) {
                return false;
            }

            if ($this->client_info->keyboard !== $this->keyboard) {
                return false;
            }
            if ($this->client_info->inline_keyboard !== $this->inline_keyboard) {
                return false;
            }
            if ($this->client_info->carousel !== $this->carousel) {
                return false;
            }
            return $this->client_info->lang_id === $this->lang_id;
        }

        return false;
    }

    /**
     * @param $haystack
     * @return ClientInfo
     */
    public function setHaystack($haystack): ClientInfo
    {
        if ($haystack instanceof DataFetcher) {
            $this->client_info = $haystack->getClientInfo();
        }

        return $this;
    }
}