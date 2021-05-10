<?php

declare(strict_types=1);

namespace Bot\Commands;

use DigitalStars\SimpleVK\SimpleVK;
use Exception;
use Labile\SimpleVKExtend\SimpleVKExtend;

/**
 * Фичи бота пишутся здесь, можно подключать трейты
 * Метод не должен возвращать значение false если он не является методом-проверкой по типу isAdmin, isChat
 * Class Commands
 * @package Bot\Commands
 */
class Commands
{

    protected SimpleVK $vk;

    public function __construct(SimpleVK $vk)
    {
        $this->vk = $vk;
    }

    /**
     * Это личное сообщение?
     * @throws Exception
     */
    public function isPrivateMessage(): bool
    {
        return $this->isChat() !== true;
    }

    /**
     * Это чат?
     * @throws Exception
     */
    public function isChat(): bool
    {
        return SimpleVKExtend::getVars('chat_id') !== null;
    }


    /**
     * Чувак нажавший на каллбек кнопку - админ?
     * @throws Exception
     */
    public function eventNoAccess(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $this->vk->eventAnswerSnackbar('Нет доступа к каллбек кнопке');
        return false;
    }

    /**
     * Это админ?
     * @throws Exception
     */
    public function isAdmin(): bool
    {
        return (bool)$this->vk
            ->isAdmin(SimpleVKExtend::getVars('user_id'), SimpleVKExtend::getVars('peer_id'));
    }

    /**
     * Это руководитель группы?
     * @return bool
     * @throws Exception
     */
    public function isManagerGroup(): bool
    {
        $admins = SimpleVKExtend::getManagersGroup($this->vk, SimpleVKExtend::getVars('group_id'));
        return in_array(SimpleVKExtend::getVars('user_id'), $admins, true);
    }
}
