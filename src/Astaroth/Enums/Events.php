<?php
declare(strict_types=1);

namespace Astaroth\Enums;

enum Events: string
{
    case MESSAGE_NEW = "message_new";
    case MESSAGE_EVENT = "message_event";
    case WALL_POST_NEW = "wall_post_new";
}