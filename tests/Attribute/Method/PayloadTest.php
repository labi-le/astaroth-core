<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Payload;
use Astaroth\Enums\PayloadValidation;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class PayloadTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }

    public function testValidate(): void
    {
        $payload = new Payload("foo", PayloadValidation::KEY_EXIST);
        $payload->setHaystack((require self::DATA_DIR)->messageNew());
        assertTrue($payload->validate());
    }
}
