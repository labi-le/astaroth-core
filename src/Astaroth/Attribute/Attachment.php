<?php

declare(strict_types=1);

namespace Astaroth\Attribute;

use Astaroth\Contracts\AttributeValidatorInterface;
use Attribute;
use JetBrains\PhpStorm\ExpectedValues;
use function count;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class Attachment implements AttributeValidatorInterface
{
    private array $haystack;

    public function __construct(
        #[ExpectedValues(values: [
            self::ALL,
            self::VIDEO,
            self::AUDIO,
            self::AUDIO_MESSAGE,
            self::DOC,
            self::GRAFFITI,
            self::STICKER,
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

    public function setHaystack($haystack): Attachment
    {
        $this->haystack = $haystack;
        return $this;
    }
}