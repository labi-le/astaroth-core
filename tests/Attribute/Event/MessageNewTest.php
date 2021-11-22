<?php

declare(strict_types=1);

namespace Attribute\Event;

use Astaroth\Attribute\Event\MessageNew;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageNewTest extends TestCase
{
    public function testValidate()
    {
        $obj = new class {
            public function getType(): string
            {
                return "message_new";
            }
        };

        $ev = (new MessageNew())->setHaystack($obj);
        assertTrue($ev->validate());
    }

    public function testSetHaystack()
    {
        $obj = new class {
            public function getType(): string
            {
                return "message_new";
            }
        };
        assertEquals(MessageNew::class,
            (new MessageNew())->setHaystack($obj)::class);
    }
}
