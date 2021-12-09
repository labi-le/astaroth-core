<?php
declare(strict_types=1);

namespace Astaroth\Foundation\Enums;

/**
 * @psalm-type Enum
 */
final class Events
{
    public const MESSAGE_NEW = "message_new";
    public const MESSAGE_EVENT = "message_event";
    public const WALL_POST_NEW = "wall_post_new";

    //just in case
    private function __construct()
    {
    }
}