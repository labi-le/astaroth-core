<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\MessageRegex;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageTest extends TestCase
{
    public function testSetHaystack()
    {
        $hs = (new MessageRegex("aboba", MessageRegex::STRICT))->setHaystack("aboba");
        assertEquals(MessageRegex::class, $hs::class);
    }

    public function testValidate()
    {
        $hs = (new MessageRegex("aboba", MessageRegex::STRICT))->setHaystack("aboba");
        assertTrue($hs->validate());
    }
}
