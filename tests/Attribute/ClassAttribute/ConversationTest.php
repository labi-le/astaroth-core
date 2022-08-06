<?php

declare(strict_types=1);

namespace Attribute\ClassAttribute;

use Astaroth\Attribute\ClassAttribute\Conversation;
use Astaroth\Enums\ConversationType;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertTrue;

class ConversationTest extends TestCase
{
    public function bench(): void
    {
        $this->testValidate();
    }

    public function testValidate(): void
    {
        $hs = new Conversation(ConversationType::ALL, $this->getTestData()->messageNew()->getPeerId());
        $hs->setHaystack($this->getTestData()->messageNew());

        assertTrue($hs->validate());
    }

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }
}
