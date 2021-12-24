<?php

declare(strict_types=1);

namespace Route\Attribute;


use Astaroth\Attribute\ClassAttribute\Conversation;
use Astaroth\Attribute\ClassAttribute\Event;
use Astaroth\Attribute\General\Description;
use Astaroth\Attribute\Method\Debug;
use Astaroth\Attribute\Method\Message;
use Astaroth\Attribute\Method\MessageRegex;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Enums\Events;
use function count;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertIsObject;
use function range;
use function uniqid;

#[Conversation]
#[Event(Events::MESSAGE_NEW)]
class testClass extends BaseCommands
{
    public function __construct(MessageNew|Event $dataConstruct = null)
    {
        parent::__construct($dataConstruct);
        assertIsObject($this->data);
    }

    #[Message("uwuwu")]
    #[Description("method to be implicitly executed")]
    public function emptyMethod(MessageNew|Event $dataEmptyMethod, Description $description): bool
    {
        assertEquals("method to be implicitly executed", $description->return());
        assertEquals("uwuwu", $dataEmptyMethod->getText());

        return true;
    }

    #[Debug]
    #[Description("desc2")]
    public function emptyMethod2(Debug $debug, Description $description): bool
    {
        assertIsArray($debug->return());
        assertEquals("desc2", $description->return());

        $stack = [];
        foreach (range(0, 10000) as $ignored) {
            $stack[] = uniqid('', true);
        }

        assertEquals(10001, count($stack));

        return true;
    }

    #[MessageRegex("/uwuwu/")]
    public function emptyMethod3(MessageRegex $regex): bool
    {
        assertEquals($regex[0], "uwuwu");
        return true;
    }
}