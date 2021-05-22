<?php


namespace Bot\Commands;


use Bot\Models\DataParser;

final class Actions extends Commands
{

    public function __construct(array $auth, DataParser $data)
    {
        parent::__construct($auth, $data);

        $member_id = $data->getAction()['member_id']?? $data->getFromId();
        $event = $data->getAction()['type'];
        if ($member_id !== null && method_exists($this, $event)) {
            $this->$event($member_id);
        }
    }


    /**
     * Пользователь присоединился к беседе по инвайт-ссылке
     * @param int $id
     * @return void
     */
    private function chat_invite_user_by_link(int $id): void
    {
        $this->chat_invite_user($id);
    }

    /**
     * Пользователь присоединился к беседе
     * @param int $id
     */
    private function chat_invite_user(int $id): void
    {
    }

    /**
     * Пользователь покинул беседу, либо был исключён кикнули
     * @param int $id
     * @return void
     */
    private function chat_kick_user(int $id): void
    {
    }

    /**
     * Обновлена аватарка
     * @param int $id
     */
    private function chat_photo_update(int $id): void
    {
    }

    /**
     * Удалена аватарка
     * @param int $id
     */
    private function chat_photo_remove(int $id): void
    {
    }

    /**
     * Закреплено сообщение
     * @param int $id
     */
    private function chat_pin_message(int $id): void
    {
    }

    /**
     * Откреплено сообщение
     * @param int $id
     */
    private function chat_unpin_message(int $id): void
    {
    }

    /**
     * Сделан скриншот
     * @param int $id
     */
    private function chat_screenshot(int $id): void
    {
    }
}