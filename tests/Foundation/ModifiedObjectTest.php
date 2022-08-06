<?php
declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\ModifiedObject;
use Astaroth\Test\TestCase;
use stdClass;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertInstanceOf;

class ModifiedObjectTest extends TestCase
{

    protected function setUp(): void
    {
        $this->modifiedObject = $this->createMock(ModifiedObject::class);
    }

    public function testReplaceObjects(): void
    {
        $this->modifiedObject->expects($this->once())->method('replaceObjects')
            ->willReturn($this->modifiedObject);

        assertInstanceOf(ModifiedObject::class, $this->modifiedObject->replaceObjects(new stdClass()));

    }

    public function testGetReplaceableObjects(): void
    {
        $this->modifiedObject->expects($this->once())->method('getReplaceableObjects')
            ->willReturn([]);

        assertCount(0, $this->modifiedObject->getReplaceableObjects());
    }

    public function testGetParameters(): void
    {
        $this->modifiedObject->expects($this->once())->method('getParameters')
            ->willReturn([]);

        assertCount(0, $this->modifiedObject->getParameters());
    }
}
