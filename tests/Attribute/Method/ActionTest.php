<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Action;
use Astaroth\Enums\ActionEnum;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class ActionTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }

    public function testValidate(): void
    {
        $payload = new Action(ActionEnum::CHAT_INVITE_USER, ["member_id" => -190405359]);

        assertTrue($payload->setHaystack((require self::DATA_DIR)->messageNew())->validate());
    }
}
