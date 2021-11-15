<?php
declare(strict_types=1);

namespace Route\Attribute;

use Astaroth\Attribute\Debug;
use Astaroth\Attribute\Description;
use Astaroth\Attribute\Event\MessageNew as TestEvent;
use Astaroth\Attribute\Message;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Parser\DataTransferObject\ClassInfo;
use Astaroth\Parser\ReflectionParser;
use Astaroth\Route\Attribute\AdditionalParameter;
use Astaroth\Route\Attribute\MethodExecutor;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

#[TestEvent]
class MethodExecutorTest extends TestCase
{

    private MethodExecutor $methodExecutor;
    private MessageNew $data;
    /**
     * @var ClassInfo[]
     */
    private array $classInfo;

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
        $this->classInfo = ReflectionParser::setClassMap([__CLASS__])->parse();
        $this->methodExecutor = new MethodExecutor(self::class, current($this->classInfo)->getMethods());
    }

    #[Message("test")]
    #[Description("method to be implicitly executed")]
    public function emptyMethod(MessageNew $data, Description $description)
    {
        assertEquals("method to be implicitly executed", $description->getResult());
        assertEquals("test", $data->getText());
    }

    public function testGetAvailableAttribute()
    {
        assertEquals(\count($this->methodExecutor->getAvailableAttribute()), 3);
    }

    public function testLaunch()
    {
        $this->methodExecutor
            ->setAvailableAttribute(Message::class, Debug::class, Description::class)
            ->setValidateData($this->data->messageNew())
            ->addExtraParameters(new AdditionalParameter("data", $this->data::class, false, $this->data))
            ->launch()
        ;
    }


    public function testSetAvailableAttribute()
    {

    }

    public function testSetValidateData()
    {
        assertEquals($this->methodExecutor->setValidateData($this->data)::class, MethodExecutor::class);
    }


    public function testAddExtraParameters()
    {
        $this->methodExecutor->addExtraParameters(new AdditionalParameter("test", "test", false));
        print_r($this->methodExecutor->getExtraParameters());
//        assertEquals( MethodExecutor::class);
    }

    public function testGetValidateData()
    {

    }
}
