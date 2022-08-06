<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\MessageRegex;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class MessageRegexTest extends TestCase
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
        $hs = (new MessageRegex('/\w{3,}/'))->setHaystack($this->getTestData()->messageNew());
        assertTrue($hs->validate());
        assertEquals($hs[0], "uwuwu");
    }
}
