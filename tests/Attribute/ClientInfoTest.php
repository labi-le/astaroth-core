<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\ClientInfo;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ClientInfoTest extends TestCase
{

    private object $client_info_obj;

    protected function setUp(): void
    {
        $json = '{
            "button_actions": [
                "text",
                "vkpay",
                "open_app",
                "location",
                "open_link",
                "callback",
                "intent_subscribe",
                "intent_unsubscribe"
            ],
            "keyboard": true,
            "inline_keyboard": false,
            "carousel": true,
            "lang_id": 0
        }';

        $this->client_info_obj = json_decode($json, false);
    }

    public function testSetHaystack()
    {
        assertEquals((new ClientInfo)->setHaystack($this->client_info_obj)::class, ClientInfo::class);
    }

    public function testValidate()
    {
        assertTrue((new ClientInfo([ClientInfo::CALLBACK], keyboard: true, inline_keyboard: false))
            ->setHaystack($this->client_info_obj)->validate());
    }
}
