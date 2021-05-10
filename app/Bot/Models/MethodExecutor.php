<?php


namespace Bot\Models;


use Exception;

class MethodExecutor
{
    /**
     * Выполнить метод\методы
     * @param string $namespace
     * @param array $methods
     * @param object $object
     * @throws Exception
     */
    public function __construct(string $namespace, array $methods, object $object)
    {
        foreach ($methods as $method) {
            preg_match('/^([^\s@]+)@([^\s@]+)$/m', $method, $matches);
            [, $class, $method] = $matches;

            $class = $namespace . $class;

            if ($matches === []) {
                throw new Exception("Неправильно указан Class@Method в CommandList");
            }

            if (!method_exists($class, $method)) {
                throw new Exception("Метод $method отсутствует в классе $class\n namespace: $namespace");
            }

            if ((new $class($object))->$method() === false) {
                break;
            }
        }
    }
}