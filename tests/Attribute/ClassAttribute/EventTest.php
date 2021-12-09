<?php

declare(strict_types=1);

namespace Attribute\ClassAttribute;

use Astaroth\Attribute\ClassAttribute\Event;
use Astaroth\Foundation\Enums\Events;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class EventTest extends TestCase
{
    public function testValidate(): void
    {
        $obj = new class {
            public function getType(): string
            {
                return Events::MESSAGE_EVENT;
            }
        };

        $ev = (new Event(Events::MESSAGE_EVENT))->setHaystack($obj);
        assertTrue($ev->validate());
    }

    public function testSetHaystack(): void
    {
        $obj = new class {
            public function getType(): string
            {
                return "message_event";
            }
        };

        assertEquals(EventTest::class,
            (new Event(Events::MESSAGE_EVENT))->setHaystack($obj)::class);
    }
}
