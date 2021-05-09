<?php
declare(strict_types=1);

namespace Bot\Commands;

/**
 * Класс для получения списка команд и пэйлоадов
 * Class CommandList
 * @package Bot\Commands
 */
final class CommandList
{
    /**
     * Массив с командами
     * [| - начинается с
     * | - похоже на
     * {} - содержит
     * |] - заканчивается на
     */
    public static function text(): array
    {
        return [

            [
                'text' => ['pr', 'print'],
                'method' => ['pr']
            ],

            [
                'text' => ['блин', 'капец', 'блять', 'ебать', 'ого'],
                'method' => ['blin']
            ],

            [
                'text' => ['/chat_reg'],
                'method' => ['isChat', 'chatRegistration']
            ],

        ];
    }

    /**
     * Массив с payload (нажатие на кнопку)
     * первый ключ - категория команды
     * payload - команда из категории
     * method - какой метод должен выполняться
     * type - тип кнопки (callback или default)
     */
    public static function payload(): array
    {
        return [


            'chat' =>
                [
                    [
                        'payload' => 'registration',
                        'method' => ['chatRegistration'],
                        'type' => 'default'
                    ],

                ],

        ];

    }
}