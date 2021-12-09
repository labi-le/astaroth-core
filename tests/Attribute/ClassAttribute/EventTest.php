<?php

declare(strict_types=1);

namespace Attribute\ClassAttribute;

use Astaroth\Attribute\ClassAttribute\Event;
use Astaroth\Foundation\Enums\Events;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class EventTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testValidate(): void
    {

        $ev = (new Event(Events::MESSAGE_NEW))->setHaystack(require self::DATA_DIR);
        assertTrue($ev->validate());
    }

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }
}
