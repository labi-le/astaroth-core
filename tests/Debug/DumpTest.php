<?php
declare(strict_types=1);

namespace Debug;

use Astaroth\Debug\Dump;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertEquals;

class DumpTest extends TestCase
{
    public function bench(): void
    {
        $this->testReturn();
    }

    public function testReturn(): void
    {
        assertEquals("test", (new Dump("test"))->return());
    }
}
