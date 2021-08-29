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
        $ev = (new MessageEvent())->setHaystack("message_event");
        assertTrue($ev->validate());
    }

    public function testSetHaystack()
    {
        assertEquals(MessageEvent::class,
            (new MessageEvent())->setHaystack("message_event")::class);
    }
}
