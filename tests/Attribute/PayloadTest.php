<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\Payload;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class PayloadTest extends TestCase
{
    public function testSetHaystack()
    {
        $payload = new Payload("bar", Payload::KEY_EXIST);

        assertEquals(Payload::class, $payload->setHaystack((require __DIR__ . "/data.php")->messageNew())::class);
    }

    public function testValidate()
    {
        $payload = new Payload("foo", Payload::KEY_EXIST);
        $payload->setHaystack((require __DIR__ . "/data.php")->messageNew());
        assertTrue($payload->validate());
    }
}
