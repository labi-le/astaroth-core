<?php
declare(strict_types=1);

namespace Auth;

use Astaroth\Auth\Configuration;
use Astaroth\Foundation\Application;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    protected function setUp(): void
    {
        $this->configuration = Configuration::set(dirname(__DIR__), Application::DEV);
    }

    public function testGetApiVersion()
    {
        assertEquals($this->configuration->getApiVersion(), "5.131");
    }

    public function testIsHandleRepeatedRequest()
    {
        assertEquals($this->configuration->isHandleRepeatedRequest(), false);
    }

    public function testGetAppNamespace()
    {
        assertEquals($this->configuration->getAppNamespace(), "App\Command");
    }

    public function testGetDatabaseHost()
    {
        assertEquals($this->configuration->getDatabaseHost(), "127.0.0.1");
    }

    public function testGetDatabaseDriver()
    {
        assertEquals($this->configuration->getDatabaseDriver(), "pdo_mysql");
    }

    public function testGetDatabaseUser()
    {
        assertEquals($this->configuration->getDatabaseUser(), "test");
    }

    public function testGetCountParallelOperations()
    {
        assertEquals($this->configuration->getCountParallelOperations(), 3);
    }

    public function testGetDatabaseUrl()
    {
        assertEquals($this->configuration->getDatabaseUrl(), "mysql://user:secret@localhost/mydb");

    }

    public function testGetCallbackSecretKey()
    {
        assertEquals($this->configuration->getCallbackSecretKey(), "1234567890qwertyuiop");
    }

    public function testGetAccessToken()
    {
        assertEquals($this->configuration->getAccessToken(), "PUT_TOKEN_FOR_TEST");
    }

    public function testGetEntityPath()
    {
        assertEquals($this->configuration->getEntityPath(), ["./App/Entity"]);
    }

    public function testGetType()
    {
        assertEquals($this->configuration->getType(), "LONGPOLL");
    }

    public function testSet()
    {
        assertEquals($this->configuration::class, Configuration::class);
    }

    public function testGetDatabasePort()
    {
        assertEquals($this->configuration->getDatabasePort(), "3386");
    }

    public function testGetCallbackConfirmationKey()
    {
        assertEquals($this->configuration->getCallbackConfirmationKey(), "2f21ed85");
    }

    public function testGetDatabasePassword()
    {
        assertEquals($this->configuration->getDatabasePassword(), "12345678");
    }

    public function testIsDebug()
    {
        assertEquals($this->configuration->isDebug(), true);
    }

    public function testGetCachePath()
    {
        assertEquals($this->configuration->getCachePath(), sys_get_temp_dir());
    }

    public function testGetDatabaseName()
    {
        assertEquals($this->configuration->getDatabaseName(), "database");
    }
}
