<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

/**
 * Validation for Attributes - Methods
 * @package Astaroth\Contracts
 */
interface AttributeValidatorInterface
{
    /**
     * Is an event that is identical haystack
     * Need to implement a specific validation for a specific attribute
     * @return bool
     */
    public function validate(): bool;

    /**
     * Haystack setter to be used for validation
     * Any data that can be validated for a specific attribute
     * @param $haystack
     * @return static
     */
    public function setHaystack($haystack): static;
}