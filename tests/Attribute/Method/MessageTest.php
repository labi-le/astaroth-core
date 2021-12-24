<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Message;
use Astaroth\Enums\MessageValidation;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class MessageTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }

    public function testValidate(): void
    {
        $hs = (new Message("uwuwu", MessageValidation::STRICT))->setHaystack((require self::DATA_DIR)->messageNew());
        assertTrue($hs->validate());
    }
}
