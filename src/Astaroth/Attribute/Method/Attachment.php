<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Method;

use Astaroth\Contracts\AttributeMethodInterface;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Enums\AttachmentEnum;
use Attribute;
use function count;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
/**
 * Attribute defining the message
 */
final class Attachment implements AttributeValidatorInterface, AttributeMethodInterface
{
    private array $haystack = [];

    public function __construct(
        public readonly AttachmentEnum $type,
        public readonly int $count = 1)
    {
    }


    public function validate(): bool
    {
        $attachments = [];
        foreach ($this->haystack as $attachment) {
            if ($attachment->type === $this->type->value) {
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