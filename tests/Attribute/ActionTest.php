<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\Action;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ActionTest extends TestCase
{
    public function testSetHaystack()
    {
        $payload = new Action(Action::CHAT_INVITE_USER, ["418618" => 12]);

        assertEquals(Action::class, $payload->setHaystack(require __DIR__ . "/data.php")::class);
    }

    public function testValidate()
    {
        $payload = new Action(Action::CHAT_INVITE_USER, ["member_id" => -190405359]);

        assertTrue($payload->setHaystack((require __DIR__ . "/data.php")->messageNew())->validate());
    }
}
