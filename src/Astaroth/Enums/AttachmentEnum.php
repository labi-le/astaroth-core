<?php
declare(strict_types=1);

namespace Astaroth\Enums;

enum AttachmentEnum: string
{
    case ALL = "all";

    case AUDIO_MESSAGE = "audio_message";
    case PHOTO = "photo";
    case DOC = "doc";
    case GRAFFITI = "graffiti";
    case VIDEO = "video";
    case AUDIO = "audio";
    case STICKER = "sticker";
    case WALL = "wall";
    case LINK = "link";
}