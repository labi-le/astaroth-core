<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\MessageNewAction;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageNewActionTest extends TestCase
{
    public function testSetHaystack()
    {
        $payload = new MessageNewAction(MessageNewAction::CHAT_KICK_USER, ["418618" => 12]);

        $emulation = new class{
            public string $type = MessageNewAction::CHAT_KICK_USER;
            public int $member_id = 418618;
        };
        assertEquals(MessageNewAction::class, $payload->setHaystack($emulation)::class);
    }

    public function testValidate()
    {
        $payload = new MessageNewAction(MessageNewAction::CHAT_KICK_USER, ["member_id" => 418618]);

        $emulation = new class{
            public string $type = MessageNewAction::CHAT_KICK_USER;
            public int $member_id = 418618;
        };
        assertTrue($payload->setHaystack($emulation)->validate());
    }
}
