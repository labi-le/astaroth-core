<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Attachment;
use Astaroth\Enums\AttachmentEnum;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertTrue;

class AttachmentTest extends TestCase
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
        $hs = (new Attachment(AttachmentEnum::PHOTO, 2))
            ->setHaystack($this->getTestData()->messageNew());

        assertTrue($hs->validate());
    }
}
