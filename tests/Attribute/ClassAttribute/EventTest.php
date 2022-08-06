<?php

declare(strict_types=1);

namespace Attribute\ClassAttribute;

use Astaroth\Attribute\ClassAttribute\Event;
use Astaroth\Enums\Events;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertTrue;

class EventTest extends TestCase
{
    public function bench(): void
    {
        $this->testValidate();
    }

    public function testValidate(): void
    {
        $ev = (new Event(Events::MESSAGE_NEW))->setHaystack($this->getTestData());
        assertTrue($ev->validate());
    }

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }
}
