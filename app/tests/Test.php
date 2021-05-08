<?php

declare(strict_types=1);


use Bot\Launcher;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public const CONFIG_DIR = __DIR__ . '/test_config.json';
    public Launcher $bot;

    public function setUp(): void
    {
        $this->bot = Launcher::setConfigFile(static::CONFIG_DIR);
    }

    public function testDir()
    {
        var_dump($this->bot->run());

    }
}
