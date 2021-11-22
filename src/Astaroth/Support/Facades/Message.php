<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\VkUtils\Builders\Message as MessageBuilder;

final class Message
{
    public function __construct(private ?int $peer_id = null)
    {

    }

    private string $text = "";
    private string $keyboard = "";

    /**
     * @var string[]
     */
    private array $attachments;

    public function text(string $message): Message
    {
        $this->text = $message;
        return $this;
    }

    public function keyboard(string $keyboard): Message
    {
        $this->keyboard = $keyboard;
        return $this;
    }

    public function attachments(string ...$attachments): Message
    {
        $this->attachments = $attachments;
        return $this;
    }

    public function send(int $id = null): array
    {
        $message = (new MessageBuilder)
            ->setPeerId($id ?? $this->peer_id)
            ->setMessage($this->text)
            ->setKeyboard($this->keyboard)
            ->setAttachment(...$this->attachments);

        return Create::new($message);
    }
}