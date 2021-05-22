<?php

declare(strict_types=1);

namespace Bot\Controller;

use Bot\Models\DataParser;

final class MessageNewController extends Controller
{
    public function __construct(DataParser $data)
    {
        if ($data->getChatId()) {
            new ChatController($data);
        } elseif ($data->getUserId()) {
            new PrivateMessageController($data);
        }

        /**
         * Поиск и выполнение команд по тексту сообщения
         */
        if ($data->getText()) {
            new CommandController($data);
        }

        /**
         * Поиск и выполнение команд по кнопке
         */
        if ($data->getPayload()) {
            new PayloadController($data);
        }
    }

}