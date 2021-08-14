<?php
declare(strict_types=1);

namespace Astaroth\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
class Attachment
{

    public function __construct(public string $type = Attachment::ALL, public int $count = 1)
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
}