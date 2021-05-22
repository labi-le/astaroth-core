<?php

declare(strict_types=1);

namespace Bot\Commands;

use Astaroth\VkUtils\Builders\MessageBuilder;
use Astaroth\VkUtils\Message;
use Astaroth\VkUtils\Uploader;
use Astaroth\VkUtils\Uploading\Doc;
use Bot\Models\DataParser;
use Exception;
use RuntimeException;

/**
 * Фичи бота пишутся здесь, можно подключать трейты
 * Метод не должен возвращать значение false если он не является методом-проверкой по типу isAdmin, isChat
 * Class Commands
 * @package Bot\Commands
 */
class Commands
{
    protected ?Uploader $uploader;
    protected ?Message $message;


    public function __construct(array $auth, protected DataParser $data)
    {
        $this->uploader = $auth['uploader'] ?? null;
        $this->message = $auth['message'] ?? null;
    }

    /**
     * Это личное сообщение?
     */
    public function isPrivateMessage(DataParser $data): bool
    {
        return $this->isChat($data) !== true;
    }

    /**
     * Это чат?
     */
    public function isChat(DataParser $data): bool
    {
        return (bool)$data->getChatId();
    }


    /**
     * Чувак нажавший на каллбек кнопку - админ?
     */
    public function eventNoAccess(DataParser $data): bool
    {
        return $this->isAdmin($data);
    }

    /**
     * Это админ?
     */
    public function isAdmin(DataParser $data): bool
    {
        try {
            $members = $this->message->request('messages.getConversationMembers', ['peer_id' => $data->getPeerId()])['response']['items'];
        } catch (Exception) {
            throw new Exception('Бот не админ в этой беседе, или бота нет в этой беседе');
        }
        foreach ($members as $key) {
            if ($key['member_id'] === $data->getFromId()) {
                return isset($key['is_owner'], $key['is_admin']);
            }
        }

        return false;
    }

    protected function reply(string $text, array $attachments = []): void
    {
        $this->message->create
        (
            (new MessageBuilder())
                ->setPeerId($this->data->getPeerId())
                ->setMessage($text)
                ->setAttachment(...$this->uploader
                    ->upload(...
                        array_map(function ($attachment) {
                            return (new Doc($attachment))->setPeerId($this->data->getPeerId());
                        }, $attachments)))
        );
    }

    /**
     * Это руководитель группы?
     * @param DataParser $data
     * @return bool
     */
    public function isManagerGroup(DataParser $data): bool
    {
        $admins = $this->getManagersGroup($data);
        return in_array($data->getFromId(), $admins, true);
    }

    protected function getManagersGroup(DataParser $data): array
    {
        try {
            $response = $this->message->request('groups.getMembers',
                [
                    'group_id' => $data->getGroupId(),
                    'filter' => 'managers'
                ]
            );

            return array_map(static fn($item) => $item['id'], $response['response']['item']);

        } catch (Exception) {
            throw new RuntimeException('Токен не имеет доступа к менеджерам группы');
        }

    }
}
