<?php
declare(strict_types=1);


namespace Astaroth\Route;

use Astaroth\Attributes\Attachment;
use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Message;
use Astaroth\Attributes\Payload;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Enums\Events;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;


/**
 * Class Attribute
 * @package Astaroth\Route
 */
class Attribute
{
    public function __construct(array $executable, DataFetcher $data)
    {
        $this->attribute($executable, $data);
    }

    /**
     * Attribute check and routing
     * @param array $classes
     * @param DataFetcher $data
     */
    private function attribute(array $classes, DataFetcher $data): void
    {
        foreach ($classes as $class) {

            foreach ($class["attribute"] as $attribute) {

                if ($this->conversationAttribute($attribute, $data) === false) {
                    break;
                }

                if ($attribute instanceof \Astaroth\Attributes\Event\MessageNew) {
                    $this->messageNew($class["instance"], $class["methods"], $data->messageNew());
                }

                if ($attribute instanceof \Astaroth\Attributes\Event\MessageEvent) {
                    $this->messageEvent($class["instance"], $class["methods"], $data->messageEvent());
                }

            }
        }
    }


    /**
     * Checks attributes for an event message_new
     * @see \Astaroth\Attributes\Event\MessageNeww
     * @param object $instance
     * @param array $methods
     * @param MessageNew $data
     */
    private function messageNew(object $instance, array $methods, MessageNew $data): void
    {
        foreach ($methods as $method) {
            foreach ($method["attribute"] as $attribute) {
                $this->messageAttribute($attribute, $instance, $method["name"], $data);
                $this->payloadAttribute($attribute, $instance, $method["name"], $data);
                $this->attachmentAttribute($attribute, $instance, $method["name"], $data);
            }
        }
    }

    /**
     * Handling the Conversation Attribute
     * @see Conversation
     * @param object $attribute
     * @param DataFetcher $data
     * @return bool|null
     */
    private function conversationAttribute(object $attribute, DataFetcher $data): ?bool
    {
        if ($attribute instanceof \Astaroth\Attributes\Conversation) {

            $concreteData = match ($data->getType()) {
                Events::MESSAGE_NEW => $data->messageNew(),
                Events::MESSAGE_EVENT => $data->messageEvent()
            };

            $type = match ($attribute->type) {
                Conversation::PERSONAL_DIALOG => $concreteData->getChatId() === null,
                Conversation::ALL => (bool)$concreteData->getPeerId(),
                Conversation::CHAT => (bool)$concreteData->getChatId()
            };

            $concreteId = match ($attribute->type) {
                Conversation::PERSONAL_DIALOG => $concreteData->getFromId(),
                Conversation::ALL => $concreteData->getPeerId(),
                Conversation::CHAT => $concreteData->getChatId()
            };

            if ($attribute->id === []) {
                return $type;
            }
            return in_array($concreteId, $attribute->id, true) && $type;

        }
        return null;
    }

    /**
     * Search payload attribute
     * @see Payload
     * @param object $attribute
     * @param object $instance
     * @param string $method
     * @param MessageNew|MessageEvent $data
     */
    private function payloadAttribute(object $attribute, object $instance, string $method, MessageNew|MessageEvent $data): void
    {
        if ($attribute instanceof \Astaroth\Attributes\Payload) {
            $payload = @json_decode((string)$data->getPayload(), true);

            if ($attribute->payload === $payload) {
                $this->execute($instance, $method, $data);
            }
        }
    }

    /**
     * Search attribute Message
     * @see Message
     * @param object $attribute
     * @param object $instance
     * @param string $method
     * @param MessageNew $data
     */
    private function messageAttribute(object $attribute, object $instance, string $method, MessageNew $data): void
    {
        if ($attribute instanceof \Astaroth\Attributes\Message) {
            $matcher = new \Astaroth\TextMatcher($attribute->message, mb_strtolower($data->getText()), $attribute->validation);
            if ($matcher->compare()) {
                $this->execute($instance, $method, $data);
            }
        }
    }

    /**
     * Checks attributes for an event message_event
     * @see \Astaroth\Attributes\Event\MessageEvent
     * @param object $instance
     * @param array $methods
     * @param MessageEvent $data
     */
    private function messageEvent(object $instance, array $methods, MessageEvent $data): void
    {
        foreach ($methods as $method) {
            foreach ($method["attribute"] as $attribute) {
                $this->payloadAttribute($attribute, $instance, $method["name"], $data);
            }
        }
    }

    /**
     * Execute methods with args...
     * @param object $instance
     * @param string $method
     * @param ...$args
     */
    private function execute(object $instance, string $method, ...$args): void
    {
        $instance->$method(...$args);
    }

    /**
     * Search attribute attachment
     * @see Attachment
     * @param object $attribute
     * @param object $instance
     * @param string $method
     * @param MessageNew $data
     */
    private function attachmentAttribute(object $attribute, object $instance, string $method, MessageNew $data): void
    {
        $attachments = [];
        if ($attribute instanceof \Astaroth\Attributes\Attachment && count($data->getAttachments()) > 0) {
            foreach ($data->getAttachments() as $attachment) {
                if ($attachment->type === $attribute->type) {
                    $attachments[] = $attachment;
                }
            }

            if (count($attachments) === $attribute->count) {
                $this->execute($instance, $method, $data);
            }
        }
    }
}