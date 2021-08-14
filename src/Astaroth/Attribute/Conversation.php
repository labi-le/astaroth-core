<?php
declare(strict_types=1);


namespace Astaroth\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * An attribute that determines whether a dialogue is a conversation, a personal message, or it doesn't matter.
 */
class Conversation
{
    /**
     * @var array|int[]
     */
    public array $id = [];

    /**
     * Conversation constructor.
     * @param int $type
     * @param int ...$id
     */
    public function __construct(public int $type = Conversation::ALL, int ...$id)
    {
        $this->id = $id;
    }

    public const ALL = 6;

    public const CHAT = 12;
    public const PERSONAL_DIALOG = 24;
}