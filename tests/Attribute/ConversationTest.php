<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\Conversation;
use Astaroth\DataFetcher\DataFetcher;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ConversationTest extends TestCase
{
    public function testValidate()
    {
        $hs = new Conversation(Conversation::PERSONAL_DIALOG, 259166248);
        $hs->setHaystack(require __DIR__ . "data.php");

        assertTrue($hs->validate());
    }

    public function testSetHaystack()
    {
        $hs = new Conversation(Conversation::PERSONAL_DIALOG, 259166248);
        $hs->setHaystack(require __DIR__ . "data.php");

        assertEquals($hs::class, $hs::class);
    }
}
