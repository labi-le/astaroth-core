<?php

declare(strict_types=1);

namespace Astaroth\Contracts;


/**
 * Validation for Attributes - Methods
 */
interface AttributeValidatorInterface
{
    /**
     * Валидация данных которую имплементит аттрибут
     * @return bool
     */
    public function validate(): bool;

    /**
     * Сеттер необходимых данных для валидации
     * @param $haystack
     * @return $this
     */
    public function setHaystack($haystack): static;
}