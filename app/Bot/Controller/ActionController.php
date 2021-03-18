<?php

declare(strict_types=1);

namespace Manager\Controller;

use Labile\SimpleVKExtend\SimpleVKExtend;

class ActionController extends ChatController
{
    /**
     * обработка action (message\\action)
     * @param array $data
     * @return void
     */
    public static function handler(array $data): void
    {
        $type = $data['type'];
        $member_id = $data['member_id'];
        if (method_exists(self::class, $type)) {
            self::$type($member_id);
        }
    }

    /**
     * Пользователь присоединился к беседе по инвайт-ссылке
     * @param $id
     * @return void
     */
    private static function chat_invite_user_by_link(int $id): void
    {
        self::chat_invite_user($id);
    }

    /**
     * Пользователь присоединился к беседе
     * @param $id
     */
    private static function chat_invite_user(int $id): void
    {
        /**
         * Если добавили бота
         */
        if ($id === -SimpleVKExtend::getVars('group_id')) {
        }

    }

    /**
     * Пользователь покинул беседу, либо был исключён кикнули
     * @param $id
     * @return void
     */
    private static function chat_kick_user(int $id): void
    {
    }

    /**
     * Обновлена аватарка
     * @param $id
     */
    private static function chat_photo_update(int $id): void
    {
    }

    /**
     * Удалена аватарка
     * @param $id
     */
    private static function chat_photo_remove(int $id): void
    {
    }

    /**
     * Закреплено сообщение
     * @param $id
     */
    private static function chat_pin_message(int $id): void
    {
    }

    /**
     * Откреплено сообщение
     * @param $id
     */
    private static function chat_unpin_message(int $id): void
    {
    }

    /**
     * Сделан скриншот
     * @param $id
     */
    private static function chat_screenshot(int $id): void
    {
    }
}