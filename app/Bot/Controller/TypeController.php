<?php

declare(strict_types=1);

namespace Bot\Controller;


use Bot\Models\DataParser;
use Exception;

final class TypeController extends Controller
{

    public function __construct(DataParser $data)
    {
        $type = $data->getType();
        if(method_exists(__CLASS__ , $type)){
            $this->$type($data);
        }
    }

    /**
     * Ивент: нажатие калбек кнопки
     * Event message_event
     * @param DataParser $data
     * @throws Exception
     */
    private function message_event(DataParser $data): void
    {
        if ($data->getPayload()) {
            new PayloadController($data, 'callback');
        }
    }

    /**
     * Ивент: Новое сообщение
     * @param DataParser $data
     * @throws Exception
     */
    private function message_new(DataParser $data): void
    {
        new MessageNewController($data);
    }

}