<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageEvent;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\State;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Parser\DataTransferObject\ClassInfo;

class EventAttributeHandler
{
    /**
     * @param ClassInfo[] $instances
     * @param DataFetcher $data
     */
    public function __construct(
        array       $instances,
        DataFetcher $data,
    )
    {
        $this->handle($instances, $data);
    }

    /**
     * AttributeOLD check and routing
     * @param ClassInfo[] $classes
     */
    private function handle(array $classes, DataFetcher $data): void
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
                    && !$attribute->setHaystack($data)->validate()
                ) {
                    break;
                }

                /**
                 * If the attribute is a MessageNew object
                 * @see \Astaroth\Attribute\Event\MessageNew
                 */
                if (
                    $attribute instanceof MessageNew &&
                    $attribute->setHaystack($data->getType())->validate()
                ) {
                    new EventDispatcher($class, $data->messageNew());
                }

                /**
                 * If the attribute is a MessageEvent object
                 * @see \Astaroth\Attribute\Event\MessageEvent
                 */
                if (
                    $attribute instanceof MessageEvent &&
                    $attribute->setHaystack($data->getType())->validate()
                ) {
                    new EventDispatcher($class, $data->messageEvent());
                }

            }
        }
    }

}