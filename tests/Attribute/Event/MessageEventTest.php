<?php

declare(strict_types=1);

namespace Attribute\Event;

use Astaroth\Attribute\Event\MessageEvent;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageEventTest extends TestCase
{

    public function testValidate()
    {
        $obj = new class {
            public function getType(): string
            {
                return "message_event";
            }
        };

        $ev = (new MessageEvent())->setHaystack($obj);
        assertTrue($ev->validate());
    }

    public function testSetHaystack()
    {
        $obj = new class {
            public function getType(): string
            {
                return "message_event";
            }
        };

        assertEquals(MessageEvent::class,
            (new MessageEvent())->setHaystack($obj)::class);
    }
}
