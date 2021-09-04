<?php

declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\Session;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;


class SessionTest extends TestCase
{
    private const ID = 418618;
    private const TYPE = "test";
    private Session $session;

    protected function setUp(): void
    {
        $this->session = new Session(self::ID, self::TYPE, sys_get_temp_dir());
    }

    protected function tearDown(): void
    {
        $this->session->purge();
    }

    public function testChangeType()
    {
        $this->session->changeType("aboba");

        assertEquals("aboba", $this->session->getType());
    }

    public function testGet()
    {
        $this->session->put("foo", "bar");
        assertEquals("bar", $this->session->get("foo"));
    }

    public function testPut()
    {
        $this->testGet();
    }

    public function testPurge()
    {
        $this->testPut();
        $this->session->purge(true);

        assertNull($this->session->get("foo"));
    }
}
