<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\Message;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageTest extends TestCase
{
    public function testSetHaystack()
    {
        $hs = (new Message("aboba", Message::STRICT))->setHaystack("aboba");
        assertEquals(Message::class, $hs::class);
    }

    public function testValidate()
    {
        $hs = (new Message("aboba", Message::STRICT))->setHaystack("aboba");
        assertTrue($hs->validate());
    }
}