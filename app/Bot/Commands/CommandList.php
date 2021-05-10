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
     *
     * method должен содержать имя класса в функцию которую необходимо выполнить
     */
    public static function text(): array
    {
        return [

            [
                'text' => ['pr', 'print'],
                'method' => ['Debug@pr']
            ],

            [
                'text' => ['привет', '[|здравствуйте'],
                'method' => ['Sample@hi']
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