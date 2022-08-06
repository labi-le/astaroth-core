<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Payload;
use Astaroth\Enums\PayloadValidation;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertTrue;

class PayloadTest extends TestCase
{
    public function bench(): void
    {
        $this->testValidate();
    }

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }

    public function testValidate(): void
    {
        $payload = new Payload("foo", PayloadValidation::KEY_EXIST);
        $payload->setHaystack($this->getTestData()->messageNew());
        assertTrue($payload->validate());
    }
}
