<?php

declare(strict_types=1);

namespace Manager\Controller;

use Manager\Commands\CommandList;
use Manager\Models\Utils;

final class MessageController extends Controller
{
    public static function parse(array $data): void
    {

        if (isset($data['chat_id'])) {
            ChatController::handler($data);
        } elseif (isset($data['user_id'])) {
            PrivateMessageController::handler($data);
        }

        /**
         * Поиск и выполнение команд по тексту сообщения
         */
        if (isset($data['text_lower'])) self::commandHandler($data['text_lower']);

        /**
         * Поиск и выполнение команд по кнопке
         */
        if (isset($data['payload'])) {
            $data['type'] === 'message_event'
                ? self::payloadHandler($data['payload'], 'callback')
                : self::payloadHandler($data['payload']);

        }
    }

    /**
     * Поиск и выполнение команд (если нашел)
     * @param string $originalText
     */
    private static function commandHandler(string $originalText): void
    {
        $list = CommandList::text();
        if (is_array($list)) {

            foreach ($list as $cmd) {
                if (!is_array($cmd['text']) && Utils::formatText(( string)$cmd['text'], $originalText)) {
                    self::method_execute($cmd['method']);
                    break;
                }

                if (is_array($cmd['text'])) {
                    foreach ($cmd['text'] as $textFromArray) {
                        if (Utils::formatText($textFromArray, $originalText)) {
                            self::method_execute($cmd['method']);
                            break;
                        }

                    }
                }
            }
        }
    }

    /**
     * Обработчик нажатий по клавиатуре
     * type == 'default' - обычные кнопки
     * type == 'callback' - калбек кнопки
     * @param array $payload
     * @param string $type
     */
    private static function payloadHandler(array $payload, string $type = 'default'): void
    {
        $payloads = CommandList::payload();
        $key = key($payload);
        $value = is_array($payload[$key]) ? current($payload[$key]) : $payload[$key];

        foreach ($payloads[$key] as $array) {
            if ($value === $array['payload'] && $array['type'] === $type) {
                self::method_execute($array['method']);
            }
        }
    }

}