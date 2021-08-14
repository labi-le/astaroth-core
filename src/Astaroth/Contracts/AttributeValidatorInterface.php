<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

use Astaroth\Attribute\NotImplementedHaystackException;

/**
 * Validation for Attributes - Methods
 */
interface AttributeValidatorInterface
{
    /**
     * Валидация данных которую имплементит аттрибут
     * @return bool
     * @throws NotImplementedHaystackException
     */
    public function validate(): bool;

    /**
     * Сеттер необходимых данных для валидации
     * @param $haystack
     * @return $this
     */
    public function setHaystack($haystack): static;
}