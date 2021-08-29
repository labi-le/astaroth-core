<?php

declare(strict_types=1);

namespace Auth;

use Astaroth\Auth\Configuration;
use Astaroth\Foundation\Application;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertIsArray;

class ConfigurationTest extends TestCase
{

    public function testGet()
    {
        assertIsArray((new Configuration(dirname(__DIR__)))->get(Application::DEV));
    }
}
