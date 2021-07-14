<?php

declare(strict_types=1);

namespace Astaroth\Interface;

/**
 * Interface TokenChangeInterface
 * @package Astaroth\Interface
 */
interface TokenChangeInterface
{
    /**
     * Change the token and get a clone of the object
     * @param string $access_token
     * @return static
     */
    public static function changeToken(string $access_token): static;
}