<?php


namespace Bot\Models;


use Exception;

class MethodExecutor
{
    /**
     * Выполнить метод\методы
     * @param string $namespace
     * @param array $methods
     * @param array $object $object
     * @param DataParser $data
     * @throws Exception
     */
    public function __construct(string $namespace, array $methods, array $object, DataParser $data)
    {
        $instance = [];
        foreach ($methods as $method) {
            preg_match('/^([^\s@]+)@([^\s@]+)$/m', $method, $matches);

            if ($matches === []) {
                throw new Exception("Неправильно указан Class@Method в CommandList");
            }

            [, $class, $method] = $matches;

            $full_class_name = $namespace . $class;

            if (!isset($instance[$class])) {
                $instance[$class] = new $full_class_name($object, $data);
            }

            if (!method_exists($instance[$class], $method)) {
                throw new Exception("Метод $method отсутствует в классе $class\n namespace: $namespace");
            }

            if ($instance[$class]->$method($data) === false) {
                break;
            }
        }
    }
}