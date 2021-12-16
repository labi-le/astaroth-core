<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;
use function count;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class Attachment implements AttributeValidatorInterface, AttributeMethodInterface
{
    private array $haystack = [];

    public function __construct(
        #[ExpectedValues(values: [
            self::ALL,
            self::VIDEO,
            self::AUDIO,
            self::AUDIO_MESSAGE,
            self::DOC,
            self::GRAFFITI,
            self::STICKER,
            self::PHOTO,
            self::WALL,
        ]
        )]
        public string $type = Attachment::ALL,
        public int $count = 1)
    {
    }

    public const ALL = "all";

    public const AUDIO_MESSAGE = "audio_message";
    public const PHOTO = "photo";
    public const DOC = "doc";
    public const GRAFFITI = "graffiti";
    public const VIDEO = "video";
    public const AUDIO = "audio";
    public const STICKER = "sticker";
    public const WALL = "wall";

    public function validate(): bool
    {
        $attachments = [];
        foreach ($this->haystack as $attachment) {
            if ($attachment->type === $this->type) {
                $attachments[] = $attachment;
            }
        }
        return count($attachments) === $this->count;
    }

    /**
     * @param $haystack
     * @return Attachment
     */
    public function setHaystack($haystack): Attachment
    {
        if ($haystack instanceof MessageNew) {
            $this->haystack = $haystack->getAttachments() ?? [];
        }

        return $this;
    }
}