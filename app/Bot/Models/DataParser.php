<?php

declare(strict_types=1);


namespace Bot\Models;

/**
 * Class DataParser
 * @package Bot\Models
 */
class DataParser
{

    private array $raw_data;

    private string $type;
    private ?int $date = null;
    private ?int $from_id = null;
    private ?int $user_id = null;
    private ?int $id = null;
    private ?bool $out = null;
    private ?int $peer_id = null;
    private ?int $chat_id = null;
    private ?string $text = null;
    private ?array $payload = null;
    private ?array $action = null;
    private ?int $conversation_message_id = null;
    private ?array $fwd_messages = null;
    private ?array $reply_message = null;
    private ?bool $important = null;
    private ?int $random_id = null;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getDate(): ?int
    {
        return $this->date;
    }

    /**
     * @return int|null
     */
    public function getFromId(): ?int
    {
        return $this->from_id;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool|null
     */
    public function getOut(): ?bool
    {
        return $this->out;
    }

    /**
     * @return int|null
     */
    public function getPeerId(): ?int
    {
        return $this->peer_id;
    }

    /**
     * @return int|null
     */
    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @return array|null
     */
    public function getPayload(): ?array
    {
        return $this->payload;
    }

    /**
     * @return array|null
     */
    public function getAction(): ?array
    {
        return $this->action;
    }

    /**
     * @return int|null
     */
    public function getConversationMessageId(): ?int
    {
        return $this->conversation_message_id;
    }

    /**
     * @return array|null
     */
    public function getFwdMessages(): ?array
    {
        return $this->fwd_messages;
    }

    /**
     * @return array|null
     */
    public function getReplyMessage(): ?array
    {
        return $this->reply_message;
    }

    /**
     * @return bool|null
     */
    public function getImportant(): ?bool
    {
        return $this->important;
    }

    /**
     * @return int|null
     */
    public function getRandomId(): ?int
    {
        return $this->random_id;
    }

    /**
     * @return array|null
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @return bool|null
     */
    public function getIsHidden(): ?bool
    {
        return $this->is_hidden;
    }

    /**
     * @return array|null
     */
    public function getClientInfo(): ?array
    {
        return $this->client_info;
    }

    /**
     * @return int|mixed
     */
    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    /**
     * @return string
     */
    public function getEventId(): string
    {
        return $this->event_id;
    }

    private ?array $attachments = null;
    private ?bool $is_hidden = null;

    private ?array $client_info = null;


    private int $group_id;

    private string $event_id;

    public function __construct(array $data)
    {
        if ($data['type'] === 'message_new') {
            $this->client_info = $data['object']['client_info'];
            $this->parseMessageNew($data['object']['message']);
        }

        if ($data['type'] === 'message_event') {
            $this->parseMessageEvent($data['object']);
        }

        $this->type = $data['type'];
        $this->raw_data = $data;
        $this->group_id = $data['group_id'];
        $this->event_id = $data['event_id'];
    }

    private function parseMessageNew(array $object): void
    {
        $this->date = $object['date'];
        $this->from_id = $object['from_id'];
        $this->id = $object['id'];
        $this->out = (bool)$object['out'];
        $this->peer_id = $object['peer_id'];

        $raw_chat_id = $object['peer_id'] - 2000000000;
        $this->chat_id = $raw_chat_id > 0 ? $raw_chat_id : null;

        $this->text = $object['text'];

        $this->payload = $object['payload'] ?? null;
        $this->action = $object['action'] ?? null;

        $this->conversation_message_id = $object['conversation_message_id'];
        $this->fwd_messages = $object['fwd_messages'];

        $this->reply_message = $object['reply_message'] ?? null;

        $this->important = $object['important'];
        $this->random_id = $object['random_id'];
        $this->attachments = $object['attachments'];
        $this->is_hidden = $object['is_hidden'];
    }

    private function parseMessageEvent(array $object): void
    {
        $this->user_id = $object['user_id'];
        $this->peer_id = $object['peer_id'];
        $this->payload = $object['payload'] ?? null;
        $this->conversation_message_id = $object['conversation_message_id'];
        $this->event_id = $object['event_id'];
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->raw_data;
    }
}