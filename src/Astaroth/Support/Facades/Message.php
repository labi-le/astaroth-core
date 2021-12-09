<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\VkUtils\Builders\Attachments\Message\AudioMessage;
use Astaroth\VkUtils\Builders\Attachments\Message\PhotoMessages;
use Astaroth\VkUtils\Builders\Message as MessageBuilder;
use Astaroth\VkUtils\Contracts\ICanBeSaved;
use function is_string;

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
    private array $attachments = [];

    public function text(string $message = ""): Message
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
        foreach ($attachments as $attachment) {
            $this->attachments[] = $attachment;
        }

        return $this;
    }

    /**
     * @param string|string[] ...$img
     * @return $this
     */
    public function addImg(string|array $img): Message
    {
        return $this->addCustomUploadableAttachments(...self::genAttachObj($img, PhotoMessages::class));
    }


    /**
     * @param string[]|string $value
     * @param string $className
     * @return ICanBeSaved[]
     */
    private static function genAttachObj(array|string $value, string $className): array
    {
        is_string($value) === false ?: $value = [$value];
        return \array_map(static fn(string $url) => new $className($url), $value);
    }

    /**
     * @param string|string[] $video
     * @return $this
     */
    public function addVoice(string|array $video): Message
    {
        return $this->addCustomUploadableAttachments(...self::genAttachObj($video, AudioMessage::class));
    }

    /**
     * @throws \Exception
     */
    public function addCustomUploadableAttachments(ICanBeSaved ...$attachments): Message
    {
        return $this->attachments(...Upload::attachments(...$attachments));
    }

    /**
     * @throws \Throwable
     */
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