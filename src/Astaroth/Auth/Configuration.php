<?php

declare(strict_types=1);


namespace Astaroth\Auth;


use Astaroth\Enums\Configuration\Additional;
use Astaroth\Enums\Configuration\ApplicationWorkMode;
use Astaroth\Enums\Configuration\CallbackRequired;
use Astaroth\Enums\Configuration\Database;
use Astaroth\Enums\Configuration\LongpollRequired;
use Astaroth\Enums\Configuration\PseudoBoolean;
use Astaroth\Enums\Configuration\Required;
use Astaroth\Enums\Configuration\Type;
use Dotenv\Dotenv;
use Exception;
use function array_map;
use function explode;
use function sys_get_temp_dir;

final class Configuration
{
    public const CONTAINER_NAMESPACE = "Astaroth\Containers";

    /**
     * @throws Exception
     */
    private function __construct(?string $dir, ApplicationWorkMode $type)
    {
        if ($type === ApplicationWorkMode::DEVELOPMENT && $dir !== null) {
            $this->parseDevEnv($dir);
        }

        if ($type === ApplicationWorkMode::PRODUCTION) {
            Additional::DEBUG->setEnv(PseudoBoolean::NO->name);
        }
    }

    /**
     * @throws Exception
     */
    public static function set(?string $dir, ApplicationWorkMode $type = ApplicationWorkMode::DEVELOPMENT): Configuration
    {
        return new Configuration($dir, $type);
    }


    /**
     * @throws Exception
     */
    private function parseDevEnv(string $dir): void
    {
        $dotenv = Dotenv::createUnsafeImmutable($dir);
        $dotenv->load();
    }

    /**
     * @throws ParameterMissingException
     */
    public function getAccessToken(): string
    {
        return Required::ACCESS_TOKEN->getEnv();
    }

    /**
     * @throws ParameterMissingException
     */
    public function getApiVersion(): string
    {
        return Required::API_VERSION->getEnv();
    }

    /**
     * @throws ParameterMissingException
     */
    public function getAppNamespace(): string
    {
        return Required::APP_NAMESPACE->getEnv();
    }

    /**
     * @return string[]
     * @throws ParameterMissingException
     */
    public function getEntityPath(): array
    {
        return array_map("trim", explode(',', Database::ENTITY_PATH->getEnv()));
    }

    public function isHandleRepeatedRequest(): bool
    {
        try {
            return CallbackRequired::HANDLE_REPEATED_REQUESTS->getEnv() === PseudoBoolean::YES->value;
        } catch (ParameterMissingException) {
            return false;
        }
    }

    public function isDebug(): bool
    {
        try {
            return Additional::DEBUG->getEnv() === PseudoBoolean::YES->value;
        } catch (ParameterMissingException) {
            return false;
        }
    }

    /**
     * @throws ParameterMissingException
     */
    public function getType(): Type
    {
        return Type::tryFrom(mb_strtolower(Required::TYPE->getEnv()));
    }

    public function getCachePath(): string
    {
        try {
            return Additional::CACHE_PATH->getEnv();
        } catch (ParameterMissingException) {
            return sys_get_temp_dir();
        }
    }

    public function getCallbackSecretKey(): ?string
    {
        try {
            return CallbackRequired::SECRET_KEY->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    /**
     * @throws ParameterMissingException
     */
    public function getCallbackConfirmationKey(): string
    {
        return CallbackRequired::CONFIRMATION_KEY->getEnv();
    }

    public function getDatabaseDriver(): ?string
    {
        try {
            return Database::DATABASE_DRIVER->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseHost(): ?string
    {
        try {
            return Database::DATABASE_HOST->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabasePort(): ?string
    {
        try {
            return Database::DATABASE_PORT->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseUrl(): ?string
    {
        try {
            return Database::DATABASE_URL->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseUser(): ?string
    {
        try {
            return Database::DATABASE_USER->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabaseName(): ?string
    {
        try {
            return Database::DATABASE_NAME->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getDatabasePassword(): ?string
    {
        try {
            return Database::DATABASE_PASSWORD->getEnv();
        } catch (ParameterMissingException) {
            return null;
        }
    }

    public function getCountParallelOperations(): int
    {
        try {
            //fork processes not work well on web servers
            if ($this->getType() === Type::CALLBACK) {
                return 0;
            }

            return (int)LongpollRequired::COUNT_PARALLEL_OPERATIONS->getEnv();
        } catch (ParameterMissingException) {
            return 0;
        }
    }
}