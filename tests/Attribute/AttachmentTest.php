<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\Attachment;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class AttachmentTest extends TestCase
{

    public function testSetHaystack()
    {
        $hs = (new Attachment(Attachment::PHOTO, 2))
            ->setHaystack((require __DIR__ . "/data.php")->messageNew());

        assertEquals(Attachment::class, $hs::class);
    }

    public function testValidate()
    {
        $hs = (new Attachment(Attachment::PHOTO, 2))
            ->setHaystack((require __DIR__ . "/data.php")->messageNew());

        assertTrue($hs->validate());
    }
}
