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
        $ev = (new MessageNew())->setHaystack("message_new");
        assertTrue($ev->validate());
    }

    public function testSetHaystack()
    {
        assertEquals(MessageNew::class,
            (new MessageNew())->setHaystack("message_new")::class);
    }
}
