<?php


namespace Bot\Models;


use Bot\Commands\CommandList;
use Bot\Commands\Commands;

class PayloadHandler
{
    /**
     * Обработчик нажатий по клавиатуре
     * type == 'default' - обычные кнопки
     * type == 'callback' - калбек кнопки
     * @param array $payload
     * @param string $type
     */
    public function __construct(array $payload, string $type = 'default')
    {
        $payloads = CommandList::payload();
        $key = key($payload);
        $value = is_array($payload[$key]) ? current($payload[$key]) : $payload[$key];

        foreach ($payloads[$key] as $array) {
            if ($value === $array['payload'] && $array['type'] === $type) {
                new MethodExecutor($array['method'], new Commands());
            }
        }
    }
}