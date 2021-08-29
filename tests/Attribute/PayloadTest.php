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
        $payload = new Payload("bar", Payload::KEY_EXISTS);

        assertEquals(Payload::class, $payload->setHaystack("foo")::class);
    }

    public function testValidate()
    {
        $payload = new Payload("foo", Payload::KEY_EXISTS);
        $payload->setHaystack(["foo" => "bar"]);
        assertTrue($payload->validate());
    }
}
