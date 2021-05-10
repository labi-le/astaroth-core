<?php


namespace Bot\Controller;


use Bot\Commands\CommandList;
use Bot\Models\MethodExecutor;
use Exception;

final class PayloadController extends Controller
{
    /**
     * Обработчик нажатий по клавиатуре
     * type == 'default' - обычные кнопки
     * type == 'callback' - калбек кнопки
     * @param array $payload
     * @param string $type
     * @param string $namespace
     * @throws Exception
     */
    public function __construct(array $payload, string $type = 'default', string $namespace = 'Bot\\Commands\\')
    {
        $payloads = CommandList::payload();
        $key = key($payload);
        $value = is_array($payload[$key]) ? current($payload[$key]) : $payload[$key];

        foreach ($payloads[$key] as $array) {
            if ($value === $array['payload'] && $array['type'] === $type) {
                new MethodExecutor($namespace, $array['method'], parent::$vk);
            }
        }
    }
}