<?php

declare(strict_types=1);

namespace Bot\Controller;

use Bot\Models\TypeHandler;

final class TypeController extends Controller
{

    public function __construct(string $type, array $data)
    {
        if(method_exists(TypeHandler::class, $type)){
            $this->$type($data);
        }
    }

    /**
     * Ивент: нажатие калбек кнопки
     * Event message_event
     * @param array $data
     */
    private function message_event(array $data): void
    {
        new MessageController($data);
    }

    /**
     * Ивент: Новое сообщение
     * @param array $data
     */
    private function message_new(array $data): void
    {
        new MessageController($data);
    }

}