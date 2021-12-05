<?php

declare(strict_types=1);


namespace Astaroth\Auth;


use Astaroth\Foundation\Application;
use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use Exception;
use JetBrains\PhpStorm\ExpectedValues;

final class Configuration
{
    private array $config;

    public const
        YES = "yes",
        NO = "no",

        DEBUG = "DEBUG",

        CACHE_PATH = "CACHE_PATH",

        CALLBACK = "CALLBACK",
        LONGPOLL = "LONGPOLL",

        TYPE = "TYPE",
        ACCESS_TOKEN = "ACCESS_TOKEN",
        API_VERSION = "API_VERSION",
        CONFIRMATION_KEY = "CONFIRMATION_KEY",
        SECRET_KEY = "SECRET_KEY",
        HANDLE_REPEATED_REQUESTS = "HANDLE_REPEATED_REQUESTS",

        APP_NAMESPACE = "APP_NAMESPACE",
        ENTITY_PATH = "ENTITY_PATH",

        DATABASE_DRIVER = "DATABASE_DRIVER",
        DATABASE_NAME = "DATABASE_NAME",
        DATABASE_USER = "DATABASE_USER",
        DATABASE_PASSWORD = "DATABASE_PASSWORD",
        DATABASE_URL = "DATABASE_URL",
        DATABASE_HOST = "DATABASE_HOST",
        DATABASE_PORT = "DATABASE_PORT",

        COUNT_PARALLEL_OPERATIONS = "COUNT_PARALLEL_OPERATIONS";

    /**
     * Configuration file structure
     */
    private const ENV_STRUCTURE =
        [
            self::DEBUG,
            self::CACHE_PATH,

            self::APP_NAMESPACE,
            self::ENTITY_PATH,
            self::ACCESS_TOKEN,
            self::TYPE,

            self::DATABASE_DRIVER,
            self::DATABASE_NAME,
            self::DATABASE_USER,
            self::DATABASE_PASSWORD,
            self::DATABASE_URL,
            self::DATABASE_HOST,
            self::DATABASE_PORT,

            self::API_VERSION,
            self::CONFIRMATION_KEY,
            self::SECRET_KEY,

            self::HANDLE_REPEATED_REQUESTS,

            self::COUNT_PARALLEL_OPERATIONS
        ];

    public const CONTAINER_NAMESPACE = "Astaroth\Containers";

    /**
     * @throws Exception
     */
    private function __construct(?string $dir, string $type)
    {
        if ($type === Application::DEV && $dir !== null) {
            $this->config = $this->parseDevEnv($dir);
        }

        if ($type === Application::PRODUCTION) {
            $this->config = $this->parseProdEnv();
        }
    }

    /**
     * @throws Exception
     */
    #[ExpectedValues(values: [Application::DEV, Application::PRODUCTION])]
    public static function set(?string $dir, string $type = Application::DEV): Configuration
    {
        return new Configuration($dir, $type);
    }


    private function parseProdEnv(): array
    {
        $env = [];
        foreach (self::ENV_STRUCTURE as $key) {
            $env[$key] = getenv($key);
        }
        return $env;
    }

    /**
     * @throws Exception
     */
    private function parseDevEnv(string $dir): array
    {
        $dotenv = Dotenv::createImmutable($dir);
        $dotenv->load();

        $this->validation($dotenv);

        return $_ENV;
    }

    /**
     * Check config for missing parameters
     * @throws Exception
     */
    private function validation(Dotenv $dotenv): void
    {
        try {
            $dotenv->required(self::ENV_STRUCTURE);
        } catch (ValidationException $e) {
            throw new ParameterMissingException($e->getMessage());
        }

        try {
            $dotenv
                ->required(self::TYPE)
                ->assert(static function ($type) {
                    return $type === self::CALLBACK || $type === self::LONGPOLL;
                }, (string)getenv(self::TYPE));

        } catch (ValidationException) {
            throw new ParameterMissingException("Bot operation type is not specified");
        }


        if ((getenv(self::TYPE) === self::CALLBACK) && empty(getenv(self::CONFIRMATION_KEY))) {
            throw new ParameterMissingException("Not specified " . self::CONFIRMATION_KEY);
        }
    }

    /**
     * @throws ParameterMissingException
     */
    public function getAccessToken(): string
    {
        return $this->getConfig(self::ACCESS_TOKEN);
    }

    /**
     * @throws ParameterMissingException
     */
    public function getApiVersion(): string
    {
        return $this->getConfig(self::API_VERSION);
    }

    /**
     * @throws ParameterMissingException
     */
    public function getAppNamespace(): string
    {
        return $this->getConfig(self::APP_NAMESPACE);
    }

    /**
     * @return string[]
     * @throws ParameterMissingException
     */
    public function getEntityPath(): array
    {
        return array_map("trim", explode(',', $this->getConfig(self::ENTITY_PATH)));
    }

    public function isHandleRepeatedRequest(): bool
    {
        try {
            return $this->getConfig(self::HANDLE_REPEATED_REQUESTS) === self::YES;
        } catch (ParameterMissingException) {
            return false;
        }
    }

    public function isDebug(): bool
    {
        try {
            return $this->getConfig(self::DEBUG) === self::YES;
        } catch (ParameterMissingException) {
            return false;
        }
    }

    /**
     * @throws ParameterMissingException
     */
    public function getType(): string
    {
        $key = $this->getConfig(self::TYPE);
        if (empty($key)) {
            return throw new ParameterMissingException("Missing parameter " . self::TYPE . " from environment\n set " . self::CALLBACK . " or " . self::LONGPOLL);
        }

        return $key;
    }

    public function getCachePath(): string
    {
        try {
            return $this->getConfig(self::CACHE_PATH);
        } catch (ParameterMissingException) {
            return sys_get_temp_dir();
        }
    }

    /**
     * @throws ParameterMissingException
     */
    public function getCallbackSecretKey(): ?string
    {
        return $this->getConfig(self::SECRET_KEY);
    }

    /**
     * @throws ParameterMissingException
     */
    public function getCallbackConfirmationKey(): string
    {
        return $this->getConfig(self::CONFIRMATION_KEY);
    }

    public function getDatabaseDriver(): ?string
    {
        try {
            return $this->getConfig(self::DATABASE_DRIVER);
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseHost(): ?string
    {
        try {
            return $this->getConfig(self::DATABASE_HOST);
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabasePort(): ?string
    {
        try {
            return $this->getConfig(self::DATABASE_PORT);
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseUrl(): ?string
    {
        try {
            return $this->getConfig(self::DATABASE_URL);
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseUser(): ?string
    {
        try {
            return $this->getConfig(self::DATABASE_USER);
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseName(): ?string
    {
        try {
            return $this->getConfig(self::DATABASE_NAME);
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabasePassword(): ?string
    {
        try {
            return $this->getConfig(self::DATABASE_PASSWORD);
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getCountParallelOperations(): int
    {
        try {
            return (int)$this->getConfig(self::COUNT_PARALLEL_OPERATIONS);
        } catch (ParameterMissingException) {
            return 0;
        }
    }


    /**
     * @param mixed $key
     * @return mixed
     * @throws ParameterMissingException
     */
    private function getConfig(mixed $key = null): mixed
    {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? throw new ParameterMissingException("Missing parameter $key from environment");
    }
}