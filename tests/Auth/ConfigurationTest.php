<?php

declare(strict_types=1);

namespace Auth;

use Astaroth\Auth\Configuration;
use Astaroth\Foundation\Application;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertIsObject;

class ConfigurationTest extends TestCase
{

    public function testGet()
    {
        assertIsObject(Configuration::set(dirname(__DIR__), Application::DEV));
    }
}
