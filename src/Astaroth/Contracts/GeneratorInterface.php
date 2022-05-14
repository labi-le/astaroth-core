<?php
declare(strict_types=1);

namespace Astaroth\Contracts;

interface GeneratorInterface
{
    public static function generate(string $namespace, string $className, string $eventName): string;
}