<?php

declare(strict_types=1);

namespace Bootstrap;

use Astaroth\Auth\Configuration;
use Astaroth\Auth\ParameterMissingException;
use Astaroth\Bootstrap\BotInstance;
use Astaroth\Foundation\Application;
use Astaroth\Longpoll\Longpoll;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class BotInstanceTest extends TestCase
{

    public function testBootstrap()
    {
        $configuration = Configuration::set(\dirname(__DIR__), Application::DEV);

        if ($configuration->getAccessToken() === "PUT_TOKEN_FOR_TEST"){
            throw new ParameterMissingException("ты забыл указать токен для тестирования");
        }
        $instance = new BotInstance($configuration);
        assertEquals(Longpoll::class, \get_class($instance->bootstrap()));
    }
}
