<?php


namespace Bot\Commands;


use Bot\Controller\CommandController;
use DigitalStars\SimpleVK\SimpleVK;
use Labile\SimpleVKExtend\SimpleVKExtend;

class Events
{
    private SimpleVK $vk;

    public function __construct(SimpleVK $vk, string $event, int|null $member_id)
    {
        $member_id = $member_id ?? SimpleVKExtend::getVars('user_id');
        if ($member_id !== null && method_exists($this, $event)) {
            $this->vk = $vk;

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
        /**
         * Если добавили бота
         */
        if ($id === -SimpleVKExtend::getVars('group_id')) {
            /**
             * commands
             */
        } else {
            $this->vk->msg('hi')->send();
        }

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