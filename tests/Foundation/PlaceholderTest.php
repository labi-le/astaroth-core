<?php

declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\Placeholder;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class PlaceholderTest extends TestCase
{

    /**
     * @throws \Throwable
     */
    public function testReplace(): void
    {
        $mock = $this
            ->createMock(Placeholder::class);

        $mock
            ->method("replace")
            ->willReturn("привет Павел");

        assertEquals("привет Павел", $mock->replace(1));
    }
}
