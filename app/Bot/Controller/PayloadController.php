<?php


namespace Bot\Controller;


use Bot\Commands\CommandList;
use Bot\Models\DataParser;
use Bot\Models\MethodExecutor;
use Exception;

final class PayloadController extends Controller
{
    /**
     * Обработчик нажатий по клавиатуре
     * type == 'default' - обычные кнопки
     * type == 'callback' - калбек кнопки
     * @param DataParser $data
     * @param string $type
     * @param string $namespace
     * @throws Exception
     */
    public function __construct(DataParser $data, string $type = 'default', string $namespace = 'Bot\\Commands\\')
    {
        $payload = $data->getPayload();

        $key = key($payload);
        $value = is_array($payload[$key]) ? current($payload[$key]) : $payload[$key];

        foreach (CommandList::payload()[$key] as $array) {
            if ($value === $array['payload'] && $array['type'] === $type) {
                new MethodExecutor($namespace, $array['method'], parent::$vk, $data);
            }
        }
    }
}