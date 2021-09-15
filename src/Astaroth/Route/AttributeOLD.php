<?php
declare(strict_types=1);


namespace Astaroth\Route;

use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\ClientInfo;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\Payload;
use Astaroth\Attribute\State;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Enums\Events;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Route\DataTransferObject\ClassInfo;


/**
 * Class AttributeOLD
 * @package Astaroth\Route
 */
class AttributeOLD
{
    /**
     * @param ClassInfo[] $executable An array of classes, methods and attributes
     * @param DataFetcher $data
     */
    public function __construct(array $executable, private DataFetcher $data)
    {
        $this->process($executable);
    }

    /**
     * AttributeOLD check and routing
     * @param ClassInfo[] $classes
     */
    private function process(array $classes): void
    {
        foreach ($classes as $class) {
            foreach ($class->getAttribute() as $attribute) {

                /**
                 * If the attribute is a Conversation or State object and the validation data is negative
                 * @see Conversation
                 * @see State
                 */
                if (
                    ($attribute instanceof Conversation || $attribute instanceof State)
                    &&
                    in_array($this->data->getType(), [Events::MESSAGE_NEW, Events::MESSAGE_EVENT], true)
                    && $attribute->setHaystack($this->data)->validate() === false
                ) {
                    break;
                }

                /**
                 * If the attribute is a MessageNew object
                 * @see \Astaroth\Attribute\Event\MessageNew
                 */
                if (
                    $attribute instanceof \Astaroth\Attribute\Event\MessageNew &&
                    $attribute->setHaystack($this->data->getType())->validate()
                ) {
                    $this->messageNew($class->getClassInstance(), $class->getMethods());
                }

                /**
                 * If the attribute is a MessageEvent object
                 * @see \Astaroth\Attribute\Event\MessageEvent
                 */
                if (
                    $attribute instanceof \Astaroth\Attribute\Event\MessageEvent &&
                    $attribute->setHaystack($this->data->getType())->validate()
                ) {
                    $this->messageEvent($class->getClassInstance(), $class->getMethods());
                }

            }
        }
    }

    /**
     * Checks attributes for an event message_new
     * @param object $instance
     * @param array $methods
     * @see \Astaroth\Attribute\Event\MessageNew
     */
    private function messageNew(object $instance, array $methods): void
    {
        $this->event($instance, $methods, function (AttributeValidatorInterface $attribute) {
            return match ($attribute::class) {
                Message::class, MessageRegex::class => $attribute->setHaystack($this->data->messageNew()->getText())->validate(),
                Payload::class => $attribute->setHaystack($this->data->messageNew()->getPayload())->validate(),
                Attachment::class => $attribute->setHaystack($this->data->messageNew()->getAttachments())->validate(),
                ClientInfo::class => $attribute->setHaystack($this->data->messageNew()->getClientInfo())->validate(),
                State::class => $attribute->setHaystack($this->data->messageNew())->validate(),
            };
        }, $this->data->messageNew());
    }

    /**
     * Checks attributes for an event message_event
     * @param object $instance
     * @param array $methods
     * @see \Astaroth\Attribute\Event\MessageEvent
     */
    private function messageEvent(object $instance, array $methods): void
    {
        $this->event($instance, $methods, function (AttributeValidatorInterface $attribute) {
            return match ($attribute::class) {
                Payload::class => $attribute->setHaystack($this->data->messageEvent()->getPayload())->validate(),
                State::class => $attribute->setHaystack($this->data->messageEvent())->validate(),
            };
        }, $this->data->messageEvent());
    }

    /**
     * General event coordinator
     * @param object $instance
     * @param array $methods
     * @param callable $event
     * @param object ...$data
     */
    private function event(
        object   $instance,
        array    $methods,
        callable $event,
        object   ...$data
    ): void
    {
        foreach ($methods as $method) {
            foreach ($method["attribute"] as $attribute) {
                $validate = $event($attribute);

                if ($validate) {
                    if (isset($method["parameters"])) {
                        /** @noinspection SlowArrayOperationsInLoopInspection */
                        $data = array_merge($data, $this->parameterInitializer($method["parameters"]));
                    }

                    $method_return = $this->execute($instance, $method["name"], ...$data);

                    if ($method_return === false) {
                        die;
                    }

                    (new ReturnResultHandler($method_return))->process();

                    break;
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


    /**
     * Adds the necessary parameters to the method that requires it
     * @param array $methodParams
     * @return object[]
     */
    private function parameterInitializer(array $methodParams): array
    {
        $extra = [];
        foreach ($methodParams as $parameter) {
            if ((in_array($parameter["type"], [MessageNew::class, MessageEvent::class], true) === false) && class_exists($parameter["type"])) {
                $extra[] = new $parameter["type"];
            }
        }
        return $extra;
    }
}