<?php

declare(strict_types=1);


namespace Bot\Controller;


use Bot\Models\DataParser;

class PrivateMessageController extends Controller
{
    /**
     * Обработчик для личных сообщений
     * Ну там подключение к базе и тд...
     * @param DataParser $data
     */
    public function __construct(DataParser $data)
    {
    }
}