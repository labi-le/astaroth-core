<?php

declare(strict_types=1);


namespace Astaroth\Auth;


use Astaroth\Foundation\Application;
use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use Exception;

class Configuration
{
    private array $config = [];

    public const YES = "yes";
    public const NO = "no";

    public const DEBUG = "DEBUG";
    public const CACHE_PATH = "CACHE_PATH";

    public const CALLBACK = "CALLBACK";
    public const LONGPOLL = "LONGPOLL";

    public const TYPE = "TYPE";
    public const ACCESS_TOKEN = "ACCESS_TOKEN";
    public const API_VERSION = "API_VERSION";
    public const CONFIRMATION_KEY = "CONFIRMATION_KEY";
    public const SECRET_KEY = "SECRET_KEY";
    public const HANDLE_REPEATED_REQUESTS = "HANDLE_REPEATED_REQUESTS";

    public const APP_NAMESPACE = "APP_NAMESPACE";
    public const ENTITY_NAMESPACE = "ENTITY_NAMESPACE";

    public const DATABASE_DRIVER = "DATABASE_DRIVER";
    public const DATABASE_NAME = "DATABASE_NAME";
    public const DATABASE_USER = "DATABASE_USER";
    public const DATABASE_PASSWORD = "DATABASE_PASSWORD";
    public const DATABASE_HOST = "DATABASE_HOST";

    /**
     * Configuration file structure
     */
    private const ENV_STRUCTURE =
        [
            self::DEBUG,
            self::CACHE_PATH,

            self::APP_NAMESPACE,
            self::ENTITY_NAMESPACE,
            self::ACCESS_TOKEN,
            self::TYPE,

            self::DATABASE_DRIVER,
            self::DATABASE_USER,
            self::DATABASE_PASSWORD,
            self::DATABASE_HOST,

            self::API_VERSION,
            self::CONFIRMATION_KEY,
            self::SECRET_KEY,

            self::HANDLE_REPEATED_REQUESTS
        ];

    public const SERVICE_NAMESPACE = "Astaroth\Services";

    private function __construct(string $dir, string $type)
    {
        $this->setConfig(match (Application::DEV) {
            Application::DEV => $this->parseDevEnv($dir),
            Application::PRODUCTION => $this->parseProdEnv()
        });
    }

    public static function set(string $dir, string $type = Application::DEV): static
    {
        return new static($dir, $type);
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
        $key = $this->getConfig(self::ACCESS_TOKEN);
        if (empty($key)) {
            return throw new ParameterMissingException("Missing parameter " . self::ACCESS_TOKEN . " from environment");
        }

        return $key;
    }

    /**
     * @throws ParameterMissingException
     */
    public function getApiVersion(): string
    {
        $key = $this->getConfig(self::API_VERSION);
        if (empty($key)) {
            return throw new ParameterMissingException("Missing parameter " . self::API_VERSION . " from environment");
        }

        return $key;
    }

    /**
     * @throws ParameterMissingException
     */
    public function getAppNamespace(): string
    {
        $key = $this->getConfig(self::APP_NAMESPACE);
        if (empty($key)) {
            return throw new ParameterMissingException("Missing parameter " . self::APP_NAMESPACE . " from environment");
        }

        return $key;
    }

    /**
     * @return string[]
     */
    public function getEntityNamespace(): array
    {
        return array_map('trim', explode(',', $this->getConfig(self::ENTITY_NAMESPACE)));
    }

    public function isHandleRepeatedRequest(): bool
    {
        return $this->getConfig(self::HANDLE_REPEATED_REQUESTS) === self::YES;
    }

    public function isDebug(): bool
    {
        return $this->getConfig(self::DEBUG) === self::YES;
    }

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
        $path = $this->getConfig(self::CACHE_PATH);
        if (empty($path)) {
            return sys_get_temp_dir();
        }

        return $path;
    }

    public function getCallbackSecretKey(): ?string
    {
        $key = $this->getConfig(self::SECRET_KEY);
        if (empty($key)) {
            return null;
        }

        return $key;
    }

    /**
     * @throws ParameterMissingException
     */
    public function getCallbackConfirmationKey(): string
    {
        $key = $this->getConfig(self::CONFIRMATION_KEY);
        if (empty($key)) {
            return throw new ParameterMissingException("Missing parameter " . self::CONFIRMATION_KEY . " from environment");
        }

        return $key;
    }

    public function getDatabaseDriver(): ?string
    {
        $key = $this->getConfig(self::DATABASE_DRIVER);
        if (empty($key)) {
            return null;
        }

        return $key;
    }

    public function getDatabaseHost(): ?string
    {
        $key = $this->getConfig(self::DATABASE_HOST);
        if (empty($key)) {
            return null;
        }

        return $key;
    }

    public function getDatabaseUser(): ?string
    {
        $key = $this->getConfig(self::DATABASE_USER);
        if (empty($key)) {
            return null;
        }

        return $key;
    }

    public function getDatabaseName(): ?string
    {
        $key = $this->getConfig(self::DATABASE_NAME);
        if (empty($key)) {
            return null;
        }

        return $key;
    }

    public function getDatabasePassword(): ?string
    {
        $key = $this->getConfig(self::DATABASE_PASSWORD);
        if (empty($key)) {
            return null;
        }

        return $key;
    }


    /**
     * @param mixed $key
     * @return mixed
     */
    private function getConfig(mixed $key = null): mixed
    {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? null;
    }

    /**
     * @param array $config
     */
    private function setConfig(array $config): void
    {
        $this->config = $config;
    }

}