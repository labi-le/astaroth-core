<?php

declare(strict_types=1);

namespace Attribute\ClassAttribute;

use Astaroth\Attribute\ClassAttribute\Conversation;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ConversationTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testValidate(): void
    {
        $hs = new Conversation(Conversation::CHAT, 1);
        $hs->setHaystack((require self::DATA_DIR)->messageNew());

        assertTrue($hs->validate());
    }

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }
}
