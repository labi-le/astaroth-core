<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\Action;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ActionTest extends TestCase
{
    public function testSetHaystack()
    {
        $payload = new Action(Action::CHAT_KICK_USER, ["418618" => 12]);

        $emulation = new class{
            public string $type = Action::CHAT_KICK_USER;
            public int $member_id = 418618;
        };
        assertEquals(Action::class, $payload->setHaystack($emulation)::class);
    }

    public function testValidate()
    {
        $payload = new Action(Action::CHAT_KICK_USER, ["member_id" => 418618]);

        $emulation = new class{
            public string $type = Action::CHAT_KICK_USER;
            public int $member_id = 418618;
        };
        assertTrue($payload->setHaystack($emulation)->validate());
    }
}
