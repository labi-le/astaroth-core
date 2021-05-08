<?php


namespace Bot\Models;


use Bot\Commands\Commands;

class MethodExecutor
{
    /**
     * Выполнить метод\методы
     * @param array|string $methods
     * @param object $object
     */
    public function __construct(array|string $methods, object $object)
    {
        if (is_array($methods)) {
            foreach ($methods as $method) {
                if ($object->$method() === false) {
                    break;
                }
            }
        } else {
            $object->$methods();
        }
    }
}