<?php

namespace Astaroth\Contracts;

interface GeneratorInterface
{
    public static function generate(string $namespace, string $className, string $eventName): void;
}