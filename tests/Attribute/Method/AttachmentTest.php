<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Attachment;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class AttachmentTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }

    public function testValidate(): void
    {
        $hs = (new Attachment(Attachment::PHOTO, 2))
            ->setHaystack((require self::DATA_DIR)->messageNew());

        assertTrue($hs->validate());
    }
}
