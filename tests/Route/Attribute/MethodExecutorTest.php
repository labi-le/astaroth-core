<?php
declare(strict_types=1);

namespace Route\Attribute;

use Astaroth\Attribute\Debug;
use Astaroth\Attribute\Description;
use Astaroth\Attribute\Event\MessageNew as TestEvent;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\State;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Parser\ReflectionParser;
use Astaroth\Route\Attribute\AdditionalParameter;
use Astaroth\Route\Attribute\MethodExecutor;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

#[TestEvent]
class MethodExecutorTest extends TestCase
{

    private MethodExecutor $methodExecutor;
    private MessageNew $data;

    public function executableMethod(Description $description)
    {
    }

    protected function setUp(): void
    {
        $data = '{
    "type": "message_new",
    "object": {
        "message": {
            "date": 1636985023,
            "from_id": 259166248,
            "id": 0,
            "out": 0,
            "peer_id": 2000000001,
            "text": "test",
            "attachments": [],
            "conversation_message_id": 30098,
            "fwd_messages": [],
            "important": false,
            "is_hidden": false,
            "random_id": 0
        },
        "client_info": {
            "button_actions": [
                "text",
                "vkpay",
                "open_app",
                "location",
                "open_link",
                "callback",
                "intent_subscribe",
                "intent_unsubscribe"
            ],
            "keyboard": true,
            "inline_keyboard": true,
            "carousel": true,
            "lang_id": 0
        }
    },
    "group_id": 201058446,
    "event_id": "2ca14315a711218d7392a46b44e22e74ad6273c3"
}';

        $this->data = (new DataFetcher(json_decode($data, false)))->messageNew();
        $classInfo = ReflectionParser::setClassMap([__CLASS__])->parse();
        $this->methodExecutor = new MethodExecutor(self::class, current($classInfo)->getMethods());

        $this->methodExecutor
            ->setCallableValidateAttribute(function ($attribute) {

                if ($attribute instanceof AttributeValidatorInterface && ($attribute::class === Message::class || $attribute::class === MessageRegex::class)) {
                    $attribute->setHaystack($this->data->getText());
                }

                if ($attribute instanceof Debug || $attribute instanceof State) {
                    $attribute->setHaystack($this->data);
                }

                return $attribute->validate();

            })
            ->addParameters(new AdditionalParameter("data", $this->data::class, false, $this->data));
    }

    #[Message("test")]
    #[Description("method to be implicitly executed")]
    public function emptyMethod(MessageNew $data, Description $description)
    {
        assertEquals("method to be implicitly executed", $description->getResult());
        assertEquals("test", $data->getText());
    }

    #[Debug]
    #[Description("desc2")]
    public function emptyMethod2(Debug $debug, Description $description)
    {
        assertInstanceOf(MessageNew::class, $debug->getResult());
        assertEquals("desc2", $description->getResult());

        $stack = [];
        foreach (range(0, 10000) as $ignored) {
            $stack[] = uniqid('', true);
        }

        assertEquals(10001, \count($stack));
    }

    public function testLaunch()
    {
        $this->methodExecutor->launch();
    }


    public function testAddParameters()
    {
        $this->methodExecutor->addParameters(new AdditionalParameter("test", "test", false));
        assertEquals($this->methodExecutor->getParameters()["test"]->getType(), "test");
    }
}
