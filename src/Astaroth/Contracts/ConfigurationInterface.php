<?php

declare(strict_types=1);


namespace Astaroth\Contracts;

use Astaroth\Enums\Configuration\Type;

interface ConfigurationInterface
{
    public function getAccessToken(): string;

    public function getApiVersion(): string;

    public function getAppNamespace(): string;

    /**
     * @return string[]
     */
    public function getEntityPath(): array;

    public function isHandleRepeatedRequest(): bool;

    public function isDebug(): bool;

    public function getType(): ?Type;

    public function getCachePath(): string;

    public function getCallbackSecretKey(): ?string;

    public function getCallbackConfirmationKey(): string;

    public function getDatabaseDriver(): ?string;

    public function getDatabaseHost(): ?string;

    public function getDatabasePort(): ?string;

    public function getDatabaseUrl(): ?string;

    public function getDatabaseUser(): ?string;

    public function getDatabaseName(): ?string;

    public function getDatabasePassword(): ?string;

    public function getCountParallelOperations(): int;

}