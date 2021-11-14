<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\Foundation\Utils;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute that always fires
 * It will be useful for dump
 */
final class Debug implements AttributeReturnInterface, AttributeValidatorInterface
{
    private mixed $haystack;

    public function __construct()
    {
    }

    public function validate(): bool
    {
        return true;
    }

    public function setHaystack($haystack): Debug
    {
        Utils::var_dumpToStdout($haystack);
        /**
         * @noinspection ForgottenDebugOutputInspection
         * @psalm-suppress ForbiddenCode
         */
        var_dump($haystack);

        $this->haystack = $haystack;
        return $this;
    }

    public function getResult(): mixed
    {
        return $this->haystack;
    }
}