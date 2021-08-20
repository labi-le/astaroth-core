<?php

declare(strict_types=1);


namespace Astaroth\Auth;


use Astaroth\Foundation\Application;
use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use Exception;

class Configuration
{
    public const YES = "yes";
    public const NO = "no";

    public const CALLBACK = "CALLBACK";
    public const LONGPOLL = "LONGPOLL";

    public const TYPE = "TYPE";
    public const ACCESS_TOKEN = "ACCESS_TOKEN";
    public const API_VERSION = "API_VERSION";
    public const CONFIRMATION_KEY = "CONFIRMATION_KEY";
    public const SECRET_KEY = "SECRET_KEY";
    public const HANDLE_REPEATED_REQUESTS = "HANDLE_REPEATED_REQUESTS";

    public const APP_NAMESPACE = "APP_NAMESPACE";

    public const SERVICE_NAMESPACE = "Astaroth\Services";

    /**
     * Configuration file structure
     */
    private const ENV_STRUCTURE =
        [
            self::APP_NAMESPACE,
            self::ACCESS_TOKEN,
            self::TYPE,

            self::API_VERSION,
            self::CONFIRMATION_KEY,
            self::SECRET_KEY,

            self::HANDLE_REPEATED_REQUESTS
        ];

    public function __construct(private ?string $dir = null)
    {
    }

    /**
     * @throws Exception
     */
    public function get(string $type): array
    {
        return match ($type) {
            Application::DEV => $this->parseDevEnv(),
            Application::PRODUCTION => $this->parseProdEnv()
        };
    }


    private function parseProdEnv(): array
    {
        $env = [];
        foreach (self::ENV_STRUCTURE as $key) {
            $env[$key] = getenv($key);
        }
        return $env;
    }

    private function parseDevEnv(): array
    {
        $dotenv = Dotenv::createImmutable($this->dir);
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

}