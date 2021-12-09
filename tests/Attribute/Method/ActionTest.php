<?php

declare(strict_types=1);

namespace Attribute\Method;

use Astaroth\Attribute\Method\Action;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ActionTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    public function testSetHaystack(): void
    {
        $payload = new Action(Action::CHAT_INVITE_USER, ["418618" => 12]);

        assertEquals(Action::class, $payload->setHaystack((require self::DATA_DIR)->messageNew())::class);
    }

    public function testValidate(): void
    {
        $payload = new Action(Action::CHAT_INVITE_USER, ["member_id" => -190405359]);

        assertTrue($payload->setHaystack((require self::DATA_DIR)->messageNew())->validate());
    }
}
