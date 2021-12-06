<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\ClientInfo;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ClientInfoTest extends TestCase
{
    public function testSetHaystack()
    {
        assertEquals((new ClientInfo)->setHaystack((require __DIR__ . "/../data.php"))::class, ClientInfo::class);
    }

    public function testValidate()
    {
        assertTrue((new ClientInfo([ClientInfo::CALLBACK], keyboard: true, inline_keyboard: false))
            ->setHaystack((require __DIR__ . "/../data.php"))->validate());
    }
}
