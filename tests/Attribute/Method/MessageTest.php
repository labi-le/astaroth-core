<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Message;
use Astaroth\Enums\MessageValidation;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertTrue;

class MessageTest extends TestCase
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
        $hs = (new Message("uwuwu", MessageValidation::STRICT))->setHaystack($this->getTestData()->messageNew());
        assertTrue($hs->validate());
    }
}
