<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Action;
use Astaroth\Enums\ActionEnum;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertTrue;

class ActionTest extends TestCase
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
        $payload = new Action(ActionEnum::CHAT_INVITE_USER, (array)$this->getTestData()->messageNew()->getAction());

        assertTrue($payload->setHaystack($this->getTestData()->messageNew())->validate());
    }
}
