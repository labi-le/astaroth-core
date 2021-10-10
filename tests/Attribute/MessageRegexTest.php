<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\MessageRegex;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageRegexTest extends TestCase
{
    public function testSetHaystack()
    {
        $hs = (new MessageRegex("/(foo)(bar)(baz)/"))->setHaystack("foobarbaz");
        assertEquals(MessageRegex::class, $hs::class);
    }

    public function testValidate()
    {
        $hs = (new MessageRegex("/(foo)(bar)(baz)/"))->setHaystack("foobarbaz");
        assertTrue($hs->validate());
    }
}
