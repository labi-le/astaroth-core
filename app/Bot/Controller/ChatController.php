<?php

declare(strict_types=1);


namespace Bot\Controller;


final class ChatController extends Controller
{
    /**
     * Обработчик для бесед
     * Ну там подключение к базе и тд...
     * @param array $data
     */
    public function __construct(array $data)
    {
        if(isset($data['action']['type'])) {
            $this->actionHandler($data['action']['type'], $data);
        }
    }

    private function actionHandler(string $action, array $data): void
    {

    }
}