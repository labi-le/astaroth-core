<?php

declare(strict_types=1);

namespace Bot\Controller;

use Exception;

final class MessageController extends Controller
{
    /**
     * @throws Exception
     */
    public function __construct(array $data)
    {
        if (isset($data['chat_id'])) {
            new ChatController($data);
        } elseif (isset($data['user_id'])) {
            new PrivateMessageController($data);
        }

        /**
         * Поиск и выполнение команд по тексту сообщения
         */
        if (isset($data['text_lower'])) {
            new CommandController($data['text_lower']);
        }

        /**
         * Поиск и выполнение команд по кнопке
         */
        if (isset($data['payload'])) {
            $data['type'] === 'message_event'
                ? new PayloadController($data['payload'], 'callback')
                : new PayloadController($data['payload']);
        }
    }

}