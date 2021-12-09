<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Message;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $hs = (new Message("uwuwu", Message::STRICT))->setHaystack((require self::DATA_DIR)->messageNew());
        assertEquals(Message::class, $hs::class);
    }

    public function testValidate(): void
    {
        $hs = (new Message("uwuwu", Message::STRICT))->setHaystack((require self::DATA_DIR)->messageNew());
        assertTrue($hs->validate());
    }
}
