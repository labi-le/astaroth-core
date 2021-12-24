<?php

namespace Astaroth\Enums\Configuration;

use Astaroth\Auth\ParameterMissingException;
use function getenv;
use function putenv;

trait EnvTrait
{
    /**
     * @throws ParameterMissingException
     */
    public function getEnv(): array|string
    {
        return getenv($this->name) ?: throw new ParameterMissingException("Missing parameter $this->name from environment");
    }

    public function setEnv(string|array $env): bool
    {
        return putenv("$this->name=$env");
    }
}