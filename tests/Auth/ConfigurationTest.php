<?php
declare(strict_types=1);

namespace Auth;

use Astaroth\Auth\Configuration;
use Astaroth\Auth\ParameterMissingException;
use Astaroth\Enums\Configuration\Type;
use Exception;
use PHPUnit\Framework\TestCase;
use function dirname;
use function PHPUnit\Framework\assertEquals;
use function sys_get_temp_dir;

class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->configuration = Configuration::set(dirname(__DIR__));
    }

    /**
     * @throws ParameterMissingException
     */
    public function testGetApiVersion(): void
    {
        assertEquals($this->configuration->getApiVersion(), "5.131");
    }

    public function testIsHandleRepeatedRequest(): void
    {
        assertEquals($this->configuration->isHandleRepeatedRequest(), false);
    }

    /**
     * @throws ParameterMissingException
     */
    public function testGetAppNamespace(): void
    {
        assertEquals($this->configuration->getAppNamespace(), "app\Command");
    }

    public function testGetDatabaseHost(): void
    {
        assertEquals($this->configuration->getDatabaseHost(), "127.0.0.1");
    }

    public function testGetDatabaseDriver(): void
    {
        assertEquals($this->configuration->getDatabaseDriver(), "pdo_mysql");
    }

    public function testGetDatabaseUser(): void
    {
        assertEquals($this->configuration->getDatabaseUser(), "test");
    }

    public function testGetCountParallelOperations(): void
    {
        assertEquals($this->configuration->getCountParallelOperations(), 3);
    }

    public function testGetDatabaseUrl(): void
    {
        assertEquals($this->configuration->getDatabaseUrl(), "mysql://user:secret@localhost/mydb");

    }

    public function testGetCallbackSecretKey(): void
    {
        assertEquals($this->configuration->getCallbackSecretKey(), "1234567890qwertyuiop");
    }

    /**
     * @throws ParameterMissingException
     */
    public function testGetAccessToken(): void
    {
        assertEquals($this->configuration->getAccessToken(), "PUT_TOKEN");
    }

    /**
     * @throws ParameterMissingException
     */
    public function testGetEntityPath(): void
    {
        assertEquals($this->configuration->getEntityPath(), ["./app/Entity"]);
    }

    /**
     * @throws ParameterMissingException
     */
    public function testGetType(): void
    {
        assertEquals($this->configuration->getType(), Type::LONGPOLL);
    }

    public function testSet(): void
    {
        assertEquals($this->configuration::class, Configuration::class);
    }

    public function testGetDatabasePort(): void
    {
        assertEquals($this->configuration->getDatabasePort(), "3386");
    }

    /**
     * @throws ParameterMissingException
     */
    public function testGetCallbackConfirmationKey(): void
    {
        assertEquals($this->configuration->getCallbackConfirmationKey(), "2f21ed85");
    }

    public function testGetDatabasePassword(): void
    {
        assertEquals($this->configuration->getDatabasePassword(), "12345678");
    }

    public function testIsDebug(): void
    {
        assertEquals($this->configuration->isDebug(), true);
    }

    public function testGetCachePath(): void
    {
        assertEquals($this->configuration->getCachePath(), sys_get_temp_dir());
    }

    public function testGetDatabaseName(): void
    {
        assertEquals($this->configuration->getDatabaseName(), "database");
    }
}
