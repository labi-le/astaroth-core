<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\ClientInfo;
use Astaroth\Enums\ClientInfoEnum;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertTrue;

class ClientInfoTest extends TestCase
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
        assertTrue((new ClientInfo([ClientInfoEnum::CALLBACK], keyboard: true, inline_keyboard: false))
            ->setHaystack($this->getTestData())->validate());
    }
}
