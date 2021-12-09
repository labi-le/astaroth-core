<?php

declare(strict_types=1);

namespace Attribute\ClassAttribute\Event;

use Astaroth\Attribute\ClassAttribute\Event\MessageNew;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageNewTest extends TestCase
{
    public function testValidate(): void
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

    public function testSetHaystack(): void
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
