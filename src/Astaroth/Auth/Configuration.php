<?php

declare(strict_types=1);


namespace Astaroth\Auth;


use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use Exception;

class Configuration
{
    /**
     * Configuration file structure
     */
    private const _STRUCTURE =
        [
            'ACCESS_TOKEN',
            'TYPE',

            'API_VERSION',
            'CONFIRMATION_KEY',
            'SECRET_KEY',

            'LOGGING_ERROR'
        ];

    public function __construct(private string $dir)
    {
    }

    /**
     * @throws Exception
     */
    public function get(): array
    {
        $dotenv = Dotenv::createImmutable($this->dir);
        $config = $dotenv->load();

        $this->validation($dotenv);

        return $config;
    }

    /**
     * Check config for missing parameters
     * @throws Exception
     */
    private function validation(Dotenv $dotenv):void
    {
        $auth = $dotenv->load();

        try {
            $dotenv->required(
                [
                    "TYPE",
                    "ACCESS_TOKEN",
                    "USER_ACCESS_TOKEN",
                    "API_VERSION"
                ])
                ->notEmpty();
        } catch (ValidationException $e) {
            throw new ParameterMissingException($e->getMessage());
        }

        try {
            $dotenv
                ->required("TYPE")
                ->assert(static fn($type) => $type === "CALLBACK" || $type === "LONGPOLL", $auth["TYPE"]);
        } catch (ValidationException) {
            throw new ParameterMissingException("Bot operation type is not specified");
        }


        if (($auth['TYPE'] === 'CALLBACK') && empty($auth['CONFIRMATION_KEY'])) {
            throw new ParameterMissingException("Not specified CONFIRMATION_KEY");
        }
    }

}