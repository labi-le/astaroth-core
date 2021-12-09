<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\MessageRegex;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageRegexTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $hs = (new MessageRegex("/(foo)(bar)(baz)/"))->setHaystack((require self::DATA_DIR)->messageNew());
        assertEquals(MessageRegex::class, $hs::class);
    }

    public function testValidate(): void
    {
        $hs = (new MessageRegex('/\w{3,}/'))->setHaystack((require self::DATA_DIR)->messageNew());
        assertTrue($hs->validate());
        assertEquals($hs[0], "uwuwu");
    }
}
