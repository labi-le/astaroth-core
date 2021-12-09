<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Payload;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class PayloadTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $payload = new Payload("bar", Payload::KEY_EXIST);

        assertEquals(Payload::class, $payload->setHaystack((require self::DATA_DIR)->messageNew())::class);
    }

    public function testValidate(): void
    {
        $payload = new Payload("foo", Payload::KEY_EXIST);
        $payload->setHaystack((require self::DATA_DIR)->messageNew());
        assertTrue($payload->validate());
    }
}
