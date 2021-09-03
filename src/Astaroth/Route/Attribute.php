<?php
declare(strict_types=1);


namespace Astaroth\Route;

use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\ClientInfo;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\Payload;
use Astaroth\Contracts\AttributeValidatorInterface;
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
    /**
     * @param array $executable An array of classes, methods and attributes
     * @param DataFetcher $data
     */
    public function __construct(array $executable, DataFetcher $data)
    {
        $this->process($executable, $data);
    }

    /**
     * Attribute check and routing
     * @param array $classes
     * @param DataFetcher $data
     */
    private function process(array $classes, DataFetcher $data): void
    {
        foreach ($classes as $class) {
            foreach ($class["attribute"] as $attribute) {

                /**
                 * If the attribute is a Conversation object and the validation data is negative
                 * @see Conversation
                 */
                if (($attribute instanceof Conversation) && in_array($data->getType(), [Events::MESSAGE_NEW, Events::MESSAGE_EVENT], true) && $attribute->setHaystack($data)->validate() === false) {
                    break;
                }

                /**
                 * If the attribute is a MessageNew object
                 * @see \Astaroth\Attribute\Event\MessageNew
                 */
                if (
                    $attribute instanceof \Astaroth\Attribute\Event\MessageNew &&
                    $attribute->setHaystack($data->getType())->validate()
                ) {
                    $this->messageNew($class["instance"], $class["methods"], $data->messageNew());
                }

                /**
                 * If the attribute is a MessageEvent object
                 * @see \Astaroth\Attribute\Event\MessageEvent
                 */
                if (
                    $attribute instanceof \Astaroth\Attribute\Event\MessageEvent &&
                    $attribute->setHaystack($data->getType())->validate()
                ) {
                    $this->messageEvent($class["instance"], $class["methods"], $data->messageEvent());
                }

            }
        }
    }

    /**
     * Checks attributes for an event message_new
     * @param object $instance
     * @param array $methods
     * @param MessageNew $data
     * @see \Astaroth\Attribute\Event\MessageNeww
     */
    private function messageNew(object $instance, array $methods, MessageNew $data): void
    {
        $this->event($instance, $methods, static function (AttributeValidatorInterface $attribute) use ($data) {
            return match ($attribute::class) {
                Message::class => $attribute->setHaystack($data->getText())->validate(),
                Payload::class => $attribute->setHaystack($data->getPayload())->validate(),
                Attachment::class => $attribute->setHaystack($data->getAttachments())->validate(),
                ClientInfo::class => $attribute->setHaystack($data->getClientInfo())->validate()
            };
        }, $data);
    }

    /**
     * Checks attributes for an event message_event
     * @param object $instance
     * @param array $methods
     * @param MessageEvent $data
     * @see \Astaroth\Attribute\Event\MessageEvent
     */
    private function messageEvent(object $instance, array $methods, MessageEvent $data): void
    {
        $this->event($instance, $methods, static function (AttributeValidatorInterface $attribute) use ($data) {
            return match ($attribute::class) {
                Payload::class => $attribute->setHaystack($data->getPayload())->validate(),
            };
        }, $data);
    }

    /**
     * General event coordinator
     * @param object $instance
     * @param array $methods
     * @param callable $event
     * @param $data
     */
    private function event(
        object   $instance,
        array    $methods,
        callable $event,
                 $data
    ): void
    {
        foreach ($methods as $method) {
            foreach ($method["attribute"] as $attribute) {
                $validate = $event($attribute);

                if ($validate) {
                    $method_return = $this->execute($instance, $method["name"], $data);

                    if ($method_return === false) {
                        die;
                    }

                    (new ReturnResultHandler($method_return))->process();
                }
            }
        }
    }

    /**
     * We call methods from the class on which the correct route is set
     * And add arguments
     * method_exist is not needed since method 100% exists
     * @param object $instance
     * @param string $method
     * @param mixed ...$args
     * @return mixed
     */
    private function execute(object $instance, string $method, ...$args): mixed
    {
        return $instance->$method(...$args);
    }
}